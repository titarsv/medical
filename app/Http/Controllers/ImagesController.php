<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Image;
use App\Http\Requests;

use App\Helpers\UnSerialize;

class ImagesController extends Controller
{
    protected $images;
    protected $destinationPath = '/assets/images';
    protected $destinationPathSmall = '/assets/cache/image';

    public function __construct(Image $images)
    {
        $this->images = $images;
    }

    /**
     * Получение массива изображений в админке
     *
     * @param Request $request
     * @return mixed
     */
    public function loadImages(Request $request)
    {
        $images = $this->images->where('type', '<>', 'thumb')
            ->orderBy('id', 'DESC')
            ->take(10)
            ->offset($request->offset)
            ->get()
            ->toArray();
        return ['data' => $images, 'current_offset' => $request->offset+count($images), 'count' => $this->images->count()];
    }

    /**
     * Загрузка изображения
     * @param Request $request
     * @return string
     */
    public function uploadImages(Request $request)
    {
        $files = $request->file('file');
        $destinationPath = public_path().$this->destinationPath;

        $response = ['data' => []];

        foreach($files as $file){
            //$newFileName = str_random(10).'.'.$file->guessExtension();
            $newFileName = $this->generate_filename($file, $destinationPath);
            $file->move($destinationPath, $newFileName);

            $image = new Image;
            $id = $image->insertGetId(
                ['title' => $file->getClientOriginalName(), 'href' => $newFileName]
            );

            $img = $image->get_image($id);
            $response['data'][] = $img;
            $img->update_image_sizes();

        }

        $response['status'] = 200;

        return json_encode($response);
    }

    /**
     * Загрузка изображения по url
     * @param string $url
     * @return mixed
     */
    public function uploadFromUrlImages($url)
    {
        $destinationPath = public_path().$this->destinationPath;
        $parts = explode('/', $url);
        $originalName = end($parts);
        $newFileName = $originalName;

        if(is_file($destinationPath.'\\'.$newFileName)) {
            $image = new Image;
            $img = $image->where('href', $newFileName)
                    ->take(1)
                    ->get()
                    ->first();
            return $img;
//            $fnparts = explode('.', $newFileName);
//            $extension = end($fnparts);
//            $i = 2;
//            $newFileName = preg_replace('/(.+)(_\(\d+\))?\.'.$extension.'/', '$1_('.$i.').'.$extension, $newFileName);
//            while(is_file($destinationPath.'\\'.$newFileName)){
//                $newFileName = preg_replace('/(.+)(_\(\d+?\))\.'.$extension.'/', '$1_('.$i.').'.$extension, $newFileName);
//                $i++;
//            }
        }

        $fp = fopen($destinationPath.'\\'.$newFileName, 'w');
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($httpCode == 404 || $httpCode == 301) {
            curl_close($ch);
            fclose($fp);
            unlink($destinationPath.'\\'.$newFileName);
            return false;
        }
        curl_close($ch);
        fclose($fp);

        $image = new Image;
        $id = $image->insertGetId(
            ['title' => $originalName, 'href' => $newFileName]
        );

        $img = $image->get_image($id);
        $img->update_image_sizes();

        return $img;
    }

    /**
     * Генерация уникального имени файла
     *
     * @param $file
     * @param string $path
     * @return mixed
     */
    public function generate_filename($file, $path = ''){
        if(empty($path))
            $path = public_path().$this->destinationPath;

        $originalName = $file->getClientOriginalName();

        if(is_file($path.'\\'.$originalName)) {
            $paths = explode('.', $originalName);
//            $extension = $file->extension();
            $extension = end($paths);
            $i = 2;
            $originalName = preg_replace('/(.+)(_\(\d+\))?\.'.$extension.'/', '$1_('.$i.').'.$extension, $originalName);
            while(is_file($path.'\\'.$originalName)){
                $originalName = preg_replace('/(.+)(_\(\d+?\))\.'.$extension.'/', '$1_('.$i.').'.$extension, $originalName);
                $i++;
            }
        }

        return $originalName;
    }

    /**
     * Удаление изображений
     * @param Request $request
     * @return mixed
     */
    public function removeImages(Request $request){
        $ids = $request->input('ids');
        if(empty($ids))
            return response()->json(['result' => 'error', 'message' => 'Не переданы идентификаторы изображений']);

        $results = [];
        $with_errors = false;

        foreach ($ids as $id) {
            if ($this->images->is_used($id)) {
                $results[] = ['id' => $id, 'status' => 'error', 'message' => 'Изображение используется и не может быть удалено'];
                $with_errors = true;
            }else {

                $image = $this->images->get_image($id);
                if ($image !== null) {
                    $image_data = $image->toArray();
                    $un_serialized = new UnSerialize($image_data['sizes']);
                    $created_sizes = $un_serialized->result();
                    if (!is_array($created_sizes))
                        $created_sizes = array();

                    foreach ($created_sizes as $size => $data) {
                        $path = public_path('assets/images/' . $data['href']);
                        if (is_file($path))
                            unlink($path);
                    }

                    $path = public_path('assets/images/' . $image_data['href']);
                    if (is_file($path))
                        unlink($path);

                    $this->images->remove_image($id);
                    $results[] = ['id' => $id, 'status' => 'deleted', 'message' => 'Изображение удалено'];
                } else {
                    $results[] = ['id' => $id, 'status' => 'error', 'message' => 'Изображение не найдено'];
                    $with_errors = true;
                }

            }
        }

        if($with_errors)
            return response()->json(['result' => 'error', 'results' => $results]);
        else
            return response()->json(['result' => 'success', 'results' => $results, 'message' => count($ids)==1?'Изображение удалено':'Все изображения удалены']);
    }

    /**
     * Инициализация обновления изображений
     * @param Image $images
     * @return mixed
     */
    public function startUpdatingImages(Image $images)
    {
        $images_count = $images->count();
        $date = date('Y-m-d H:i:s');

        return response()->json(['result' => 'success', 'count' => $images_count])->withCookie(cookie()->forever('start_img_update', $date));
    }

    /**
     * Обновление одного изображения
     * @param Request $request
     * @param Image $images
     * @return mixed
     */
    public function updateImageSize(Request $request, Image $images)
    {
        $start_img_update = $request->cookie('start_img_update');
        if(empty($start_img_update)){
            return response()->json(['result' => 'error', 'massage' => 'Отсутствует дата начала обновления']);
        }

        $image = $images->get_not_updated_image($start_img_update);
        if($image === null)
            return response()->json(['result' => 'end', 'massage' => 'Нет изображений для обновления']);

        $image->update_image_sizes();

        return response()->json(['result' => 'success', 'progress' => $images->get_updating_progress($start_img_update)]);
    }
}