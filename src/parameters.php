<?php
require_once __DIR__.'/config.php';

$app['url'] = 'http://www.lineadecodigo.es';
$app['name'] = 'Linea de código';
$app['description'] = 'Noticias sobre tecnología e internet';
$app['google_verification'] = '<meta name="google-site-verification" content="sdIPbTU_NTJziWKd2pW2iXz0wiA5pfVT5jJwVfibF_0" />';
$app['yandex_verification'] = '<meta name="yandex-verification" content="1924271e3eb9e058" />';
$app[''] = '';

$app['analytics'] = "<script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-4862846-26', 'auto');
        ga('send', 'pageview');
</script>";
$app['banner_mobile'] = '<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<script>
  (adsbygoogle = window.adsbygoogle || []).push({
    google_ad_client: "ca-pub-6904186947817626",
    enable_page_level_ads: true
  });
</script>';
$app['banner_header'] = '<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
    <!-- Una sola página - 3 (para móviles, www.lineadecodigo.es) -->
    <ins class="adsbygoogle"
         style="display:block"
         data-ad-client="ca-pub-6904186947817626"
         data-ad-slot="9308815995"
         data-ad-format="auto"></ins>
    <script>
        (adsbygoogle = window.adsbygoogle || []).push({});
    </script>';
$app['banner_between_posts'] = '<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                <ins class="adsbygoogle"
                     style="display:block"
                     data-ad-client="ca-pub-6904186947817626"
                     data-ad-slot="3262282391"
                     data-ad-format="auto"></ins>
                <script>
                    (adsbygoogle = window.adsbygoogle || []).push({});
                </script>
                ';
$app['banner_in_single_post'] = '<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>

                <ins class="adsbygoogle"
                     style="display:block"
                     data-ad-client="ca-pub-6904186947817626"
                     data-ad-slot="3262282391"
                     data-ad-format="auto"></ins>
                <script>
                    (adsbygoogle = window.adsbygoogle || []).push({});
                </script>';