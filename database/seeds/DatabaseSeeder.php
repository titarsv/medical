<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(ArticlesTableSeeder::class);
        $this->call(AttributesTableSeeder::class);
        $this->call(AttributeValuesTableSeeder::class);
        $this->call(CategoriesTableSeeder::class);
        $this->call(ImageTableSeeder::class);
        $this->call(GalleriesTableSeeder::class);
        $this->call(ModulesTableSeeder::class);
        $this->call(OrderStatusSeeder::class);
        $this->call(PagesTableSeeder::class);
        $this->call(ProductAttributesTableSeeder::class);
        $this->call(ProductsReviewSeeder::class);
        $this->call(ProductTableSeeder::class);
        $this->call(SentinelUsersTableSeeder::class);
        $this->call(SettingsTableSeeder::class);
        $this->call(UserDataSeeder::class);
        $this->call(UnitTableSeeder::class);
        $this->call(UnitProductsTableSeeder::class);
        $this->call(UnitCategoriesTableSeeder::class);
        $this->call(SchemeTableSeeder::class);
        $this->call(SchemeProductSeeder::class);
    }
}
