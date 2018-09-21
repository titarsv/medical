<head>
    <meta charset="utf-8">

    @yield('meta')

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <!-- Template Basic Images Start -->
    <meta property="og:image" content="/images/logo(color).png">
    <link rel="shortcut icon" href="/images/favicon.ico" type="image/x-icon">
    <!-- Template Basic Images End -->

    <!-- Load CSS, CSS Localstorage & WebFonts Main Function -->
    <script>!function(e){"use strict";function t(e,t,n){e.addEventListener?e.addEventListener(t,n,!1):e.attachEvent&&e.attachEvent("on"+t,n)};function n(t,n){return e.localStorage&&localStorage[t+"_content"]&&localStorage[t+"_file"]===n};function a(t,a){if(e.localStorage&&e.XMLHttpRequest)n(t,a)?o(localStorage[t+"_content"]):l(t,a);else{var s=r.createElement("link");s.href=a,s.id=t,s.rel="stylesheet",s.type="text/css",r.getElementsByTagName("head")[0].appendChild(s),r.cookie=t}}function l(e,t){var n=new XMLHttpRequest;n.open("GET",t,!0),n.onreadystatechange=function(){4===n.readyState&&200===n.status&&(o(n.responseText),localStorage[e+"_content"]=n.responseText,localStorage[e+"_file"]=t)},n.send()}function o(e){var t=r.createElement("style");t.setAttribute("type","text/css"),r.getElementsByTagName("head")[0].appendChild(t),t.styleSheet?t.styleSheet.cssText=e:t.innerHTML=e}var r=e.document;e.loadCSS=function(e,t,n){var a,l=r.createElement("link");if(t)a=t;else{var o;o=r.querySelectorAll?r.querySelectorAll("style,link[rel=stylesheet],script"):(r.body||r.getElementsByTagName("head")[0]).childNodes,a=o[o.length-1]}var s=r.styleSheets;l.rel="stylesheet",l.href=e,l.media="only x",a.parentNode.insertBefore(l,t?a:a.nextSibling);var c=function(e){for(var t=l.href,n=s.length;n--;)if(s[n].href===t)return e();setTimeout(function(){c(e)})};return l.onloadcssdefined=c,c(function(){l.media=n||"all"}),l},e.loadLocalStorageCSS=function(l,o){n(l,o)||r.cookie.indexOf(l)>-1?a(l,o):t(e,"load",function(){a(l,o)})}}(this);</script>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        body{
            opacity: 0 !important;
            transition: opacity 0.1s ease-in 0s;
        }
        .siteHeader{position:relative;background-image:linear-gradient(270deg,#907dde,#4abaee)}.siteHeader-info{padding:10px 0;background-color:#f4f7fa}@media screen and (min-width:64em){.siteHeader-info{padding:15px 0}}.siteHeader-nav{padding:10px 0;border-top:1px solid hsla(0,0%,100%,.1);border-bottom:1px solid #fff}.siteHeader-nav .siteHeader-btn{display:inline-block;margin-right:10px;float:left;height:21px}.siteHeader-nav .siteHeader-btn span{display:block;width:30px;height:3px;background:#fff}.siteHeader-nav .siteHeader-btn span:nth-child(2){position:relative;top:6px}.siteHeader-nav .siteHeader-btn span:nth-child(3){position:relative;top:12px}.siteHeader-nav a{color:#fff;font-weight:600}.siteHeader-nav.is-open ul{visibility:visible;opacity:1}.siteHeader-cat-item{overflow:hidden;font-size:12px;line-height:21px;vertical-align:middle}.siteHeader-items{position:relative;text-align:right}.siteHeader-items .btn{margin:0}@media screen and (min-width:48em){.siteHeader-items .btn{padding:10px 15px}}@media screen and (min-width:64em){.siteHeader-items .btn{padding:14px 34px}}.siteHeader-items ul{list-style:none;padding:15px 0;margin:0 -10px;visibility:hidden;opacity:0;position:absolute;transition:all .3s ease-in-out;top:55px;left:0;right:0;text-align:left;z-index:10;background-image:linear-gradient(270deg,#907dde,#4abaee);box-shadow:0 24px 24px 0 rgba(0,0,0,.1)}@media screen and (min-width:48em){.siteHeader-items ul{visibility:visible;opacity:1;position:relative;top:0;background:none;box-shadow:none;padding:0;margin:0 -22px}}.siteHeader-items ul li{padding:5px 15px}@media screen and (min-width:48em){.siteHeader-items ul li{display:inline-block;padding:5px}}@media screen and (min-width:64em){.siteHeader-items ul li{padding:5px 9px}}@media screen and (min-width:75em){.siteHeader-items ul li{padding:5px 22px}}@media screen and (min-width:48em){.siteHeader-items ul a{font-size:10px}}@media screen and (min-width:64em){.siteHeader-items ul a{font-size:12px}}.siteHeader-search{text-align:right;position:relative}@media screen and (min-width:48em){.siteHeader-search{text-align:center}}.siteHeader-search input{display:inline-block;padding:10px;max-width:145px;border:1px solid #c0c0ca}@media screen and (min-width:48em){.siteHeader-search input{max-width:none;padding:10px 30px}}@media screen and (min-width:64em){.siteHeader-search input{padding:19px 30px;min-width:70%}}.siteHeader-search button{padding:10px;margin:0;background-color:#7faeea}@media screen and (min-width:64em){.siteHeader-search button{padding:13px 35px}}.siteHeader-search:before{left:0}.siteHeader-search:after,.siteHeader-search:before{content:"";width:1px;height:106px;position:absolute;background-color:#ddd;top:-33px}.siteHeader-search:after{right:0}@media screen and (max-width:1200px){.siteHeader-search:after,.siteHeader-search:before{height:92px}}@media screen and (max-width:1024px){.siteHeader-search:after,.siteHeader-search:before{height:79px}}@media screen and (max-width:768px){.siteHeader-search:after,.siteHeader-search:before{display:none}}.siteHeader-numbers{text-align:right}.siteHeader-numbers a{display:block;color:#8490df;font-size:11px}@media screen and (min-width:64em){.siteHeader-numbers a{font-size:14px}}.siteHeader-action{padding:10px 0;float:left;min-width:50%}@media screen and (min-width:48em){.siteHeader-action,.siteHeader-nav-mobButton{display:none}}.siteHeader-catalogue{position:absolute;background-image:linear-gradient(270deg,#907dde,#4abaee);left:-100%;right:0;top:165px;z-index:100;padding:20px 0;box-shadow:0 24px 24px 0 rgba(0,0,0,.1);visibility:hidden;opacity:0;transition:all .3s ease-in-out}@media screen and (min-width:48em){.siteHeader-catalogue{top:122px;padding:40px 0}}@media screen and (min-width:64em){.siteHeader-catalogue{top:162px}}.siteHeader-catalogue ul{list-style:none;margin:0;padding:0}.siteHeader-catalogue ul li{padding:5px 0}.siteHeader-catalogue ul li a{color:#fff;font-size:12px;transition:all .3s ease-in-out}@media screen and (min-width:64em){.siteHeader-catalogue ul li a{font-size:14px}}.siteHeader-catalogue ul li a:focus,.siteHeader-catalogue ul li a:hover{text-decoration:underline;font-weight:700}.siteHeader-catalogue.is-open{opacity:1;visibility:visible;left:0}
        .btn, button, input[type="button"], input[type="reset"], input[type="submit"] {
            display: inline-block;
            margin: 12px 0;
            padding: 14px 34px;
            text-align: center;
            text-decoration: none;
            font-family: Open Sans,sans-serif;
            font-size: 12px;
            font-weight: 700;
            line-height: 1;
            border: 2px solid #f2f2f2;
            color: #807d90;
            transition: all .3s ease-out;
            appearance: none;
            border-radius: 55px;
            background: none;
        }
        .btn.btn-third, button.btn-third, input[type="button"].btn-third, input[type="reset"].btn-third, input[type="submit"].btn-third {
            background: #9985ec;
            color: #fff;
            border: 2px solid #9985ec;
        }
        .btn.btn-secondary, button.btn-secondary, input[type="button"].btn-secondary, input[type="reset"].btn-secondary, input[type="submit"].btn-secondary {
            background: #eedf3e;
            color: #2e294a;
            border: 2px solid #eedf3e;
        }
        .productContainer-img img{
            max-width: 300px;
            width: 100%;
            margin: 0 auto;
        }
        .productContainer-img .slick-slide{
            max-width: 580px;
        }
        .newsList:not(.slick-initialized){
            max-height: 336px;
            overflow: hidden;
        }
    </style>

    <!-- Load Custom CSS Compiled without JS Start -->
    <noscript>
        <link rel="stylesheet" href="/assets/css/application.css">
    </noscript>

    <!-- Load Custom CSS Start -->
    <script>loadCSS( "/assets/css/application.css", false, "all" );</script>
    <!-- Load Custom CSS End -->

    <script type="application/ld+json">
    {
        "@context": "http://schema.org",
        "@type": "HardwareStore",
        "name": "LabOborud",
        "address": {
            "@type": "PostalAddress",
            "streetAddress": "Украина, ул. Академика Павлова 120Ж",
            "addressLocality": "Харьков",
            "addressRegion": "Харьковская",
            "postalCode": "61023"
        },
        "image": "https://lab-oborud.com/images/logo(color).png",
        "telePhone": "099-235-65-13",
        "faxNumber": "098-640-77-30",
        "url": "lab-oborud.com",
        "paymentAccepted": [ "cash", "credit card", "invoice" ],
        "openingHours": "Mo,Tu,We,Th,Fr 09:00-18:00",
        "geo": {
            "@type": "GeoCoordinates",
            "latitude": "50.005823",
            "longitude": "36.314496"
        },
        "priceRange":"$$$"
    }
    </script>

    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
                new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
            'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-NXG38R3');</script>
    <!-- End Google Tag Manager -->
</head>