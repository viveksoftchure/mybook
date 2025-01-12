<?php

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Convert Text URLs to Links</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            var urlPattern = /(https?:\/\/[^\s]+)/g;

            $('body').html(function(index, html) {
                return html.replace(urlPattern, function(url) {
                    return '<a href="' + url + '" target="_blank">' + url + '</a><br>';
                });
            });
        });
    </script>
</head>
<body>
https://dore-jquery-web.coloredstrategies.com/Dashboard.Content.html
https://spruko.com/demo/ynex/blazor/dist/html/full-calendar.html
https://uxliner.com/xtreamer/demo/main/pages-404.html
https://templates.iqonic.design/datum/html/backend/auth-login.html
https://demos.pixinvent.com/vuexy-html-admin-template/html/vertical-menu-template/
https://demos.pixinvent.com/vuexy-html-admin-template/html/vertical-menu-template/auth-login-cover.html
https://themesbrand.com/velzon/html/master/auth-signin-basic.html
https://able-pro-material-next-ts-navy.vercel.app/auth/login2
https://mannatthemes.com/approx/default/index.html#
https://ekash.vercel.app/index.html
</body>
</html>
