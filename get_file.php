<?php
/*==========================*\
# Clone Coded by AtakanCan   #
\*==========================*/

# Include required files
require_once('curl.php');
require_once('includes/functions.php');
require_once('includes/config.php');

# Make sure that a video id has been passed
if(isset($_POST['videoid'])) {
    $my_id = $_POST['videoid'];
} else {
    exit('No video id has been passed!</p>');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <!--
        Youtube Converter - Youtube Downloader
        Copyright 2014 AtakanCan - All rights reserve
        You are NOT allowed to remove or change
        the position of this copyright.
    -->

    <meta charset="windows-1251">
    <meta name="generator" content="AtakanCan - Youtube Converter" />
    <title>������� ����� � YOUTUBE.COM</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">

    <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="css/bootstrap-responsive.min.css" rel="stylesheet" type="text/css" />

    <link href="css/font-awesome.css" rel="stylesheet">
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,400,600" rel="stylesheet">

    <link href="css/style.css" rel="stylesheet" type="text/css">
    <link href="css/pages/signin.css" rel="stylesheet" type="text/css">
</head>

<body>

<div class="account-container register">
    <div class="content clearfix">
        <form>
            <h1 style="text-align: center">�������</h1>
            
            <p>
              <?php

            /* First get the video info page for this video id */
            $my_video_info = 'http://www.youtube.com/get_video_info?&video_id='. $my_id;
            $my_video_info = curlGet($my_video_info);
            $thumbnail_url = $title = $url_encoded_fmt_stream_map = $type = $url = '';

            parse_str($my_video_info);
            $my_title = $title;

            if(isset($url_encoded_fmt_stream_map)) {
                /* Now get the url_encoded_fmt_stream_map, and explode on comma */
                $my_formats_array = explode(',',$url_encoded_fmt_stream_map);

            } else {
                echo '<p>No encoded format stream found.</p>';
                echo '<p>Here is what we got from YouTube:</p>';
                echo $my_video_info;
            }
            if (count($my_formats_array) == 0) {
                echo '<p>No format stream map found - was the video id correct?</p>';
                exit;
            }

            /* create an array of available download formats */
            $avail_formats[] = '';
            $i = 0;
            $ipbits = $ip = $itag = $sig = $quality = '';
            $expire = time();

            foreach($my_formats_array as $format) {
                parse_str($format);
                $avail_formats[$i]['itag'] = $itag;
                $avail_formats[$i]['quality'] = $quality;
                $type = explode(';',$type);
                $avail_formats[$i]['type'] = $type[0];
                $avail_formats[$i]['url'] = urldecode($url) . '&signature=' . $sig;
                parse_str(urldecode($url));
                $avail_formats[$i]['expires'] = date("G:i:s T", $expire);
                $avail_formats[$i]['ipbits'] = $ipbits;
                $avail_formats[$i]['ip'] = $ip;
                $i++;
            }

            # Make sure that there are available downloads
            $downloads = count($avail_formats);
            if($downloads == 1) {
                # No download available
                echo'<div class="alert alert-danger"><strong>Error!</strong> Unable to download this video. <br/> Looks like it\'s protected. Sorry!</div>';
            } else {
                echo '<div style="text-align: center">' .$title. '<br/>
                      <img src="'. $thumbnail_url .'" border="0" hspace="2" vspace="2"></div><br/>';
                echo '<table class="table table-striped table-bordered">
                        <thead>
                        <tr>
                            <th style="text-align: center">������ </th>
                            <th style="text-align: center">������</th>
                            <th style="text-align: center">�������</th>

                        </tr>
                        </thead>
                        <tbody>';

                /* now that we have the array, print the options */
                for ($i = 0; $i < count($avail_formats); $i++) {
                    # Get the filesize, to make sure we can download it for the mp3 conversion
                    $file_size =  remotefileSize($avail_formats[$i]['url']);

                    echo '
                    <tr>
                        <td style="text-align: center">' . $avail_formats[$i]['type'] . '</td>
                        <td style="text-align: center">' . $avail_formats[$i]['quality'] . '</td>
                        <td class="td-actions" style="text-align: center">
                        <a class="btn btn-small btn-success" data-toggle="tooltip" title="������� �����" href="download.php?mime=' . $avail_formats[$i]['type'] .'&title='. urlencode($my_title) .'&token=' . base64_encode($avail_formats[$i]['url']) . '"><i class="btn-icon-only icon-film"></i></a>';

                    # Display the MP3 download button if a CloudConvert API key has been set
                    if($config['cloud_convert_api_key'] != 'INSERT YOUR API KEY HERE' && !empty($config['cloud_convert_api_key']) && $avail_formats[$i]['type'] != 'video/3gpp' && $file_size > 0 && $file_size) {
                        echo ' <a class="btn btn-danger btn-small" data-toggle="tooltip" title="������� MP3" href="download.php?type=mp3&mime=' . $avail_formats[$i]['type'] .'&title='. urlencode($my_title) .'&token=' . base64_encode($avail_formats[$i]['url']) . '"><i class="btn-icon-only icon-music"> </i></a>';
                    }
                    echo '</td></tr>';
                }
                echo '</ul>';
            }
            ?>
              
              </tbody>
              </table>
            </p>
          <p><center>

<div style="display:none;"> <a href="http://seocola.ru">������� </a></div>


</center></p>
        </form>
    </div>
</div>


<!-- Text Under Box -->
<div class="login-extra" style="text-align: center">
    <a href="index.php">�����</a>
</div> <!-- /login-extra -->


<script src="js/jquery-1.7.2.min.js"></script>
<script src="js/bootstrap.js"></script>


<script type="text/javascript">
    $(function () {
        $("[data-toggle='tooltip']").tooltip();
    });
</script>

</body>
</html>