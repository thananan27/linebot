<?php
// กรณีต้องการตรวจสอบการแจ้ง error ให้เปิด 3 บรรทัดล่างนี้ให้ทำงาน กรณีไม่ ให้ comment ปิดไป
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
 
// include composer autoload
// require_once '../vendor/autoload.php';
require_once './vendor/autoload.php';
 
// การตั้งเกี่ยวกับ bot
require_once 'bot_settings.php';
 
// กรณีมีการเชื่อมต่อกับฐานข้อมูล
//require_once("dbconnect.php");
 
///////////// ส่วนของการเรียกใช้งาน class ผ่าน namespace
use LINE\LINEBot;
use LINE\LINEBot\HTTPClient;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
//use LINE\LINEBot\Event;
//use LINE\LINEBot\Event\BaseEvent;
//use LINE\LINEBot\Event\MessageEvent;
use LINE\LINEBot\MessageBuilder;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use LINE\LINEBot\MessageBuilder\StickerMessageBuilder;
use LINE\LINEBot\MessageBuilder\ImageMessageBuilder;
use LINE\LINEBot\MessageBuilder\LocationMessageBuilder;
use LINE\LINEBot\MessageBuilder\AudioMessageBuilder;
use LINE\LINEBot\MessageBuilder\VideoMessageBuilder;
use LINE\LINEBot\ImagemapActionBuilder;
use LINE\LINEBot\ImagemapActionBuilder\AreaBuilder;
use LINE\LINEBot\ImagemapActionBuilder\ImagemapMessageActionBuilder ;
use LINE\LINEBot\ImagemapActionBuilder\ImagemapUriActionBuilder;
use LINE\LINEBot\MessageBuilder\Imagemap\BaseSizeBuilder;
use LINE\LINEBot\MessageBuilder\ImagemapMessageBuilder;
use LINE\LINEBot\MessageBuilder\MultiMessageBuilder;
use LINE\LINEBot\TemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\DatetimePickerTemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateMessageBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ButtonTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ConfirmTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ImageCarouselTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ImageCarouselColumnTemplateBuilder;
 
// เชื่อมต่อกับ LINE Messaging API
$httpClient = new CurlHTTPClient(LINE_MESSAGE_ACCESS_TOKEN);
$bot = new LINEBot($httpClient, array('channelSecret' => LINE_MESSAGE_CHANNEL_SECRET));
 
// คำสั่งรอรับการส่งค่ามาของ LINE Messaging API
$content = file_get_contents('php://input');
 
// แปลงข้อความรูปแบบ JSON  ให้อยู่ในโครงสร้างตัวแปร array
$events = json_decode($content, true);

if(!is_null($events)){
    // ถ้ามีค่า สร้างตัวแปรเก็บ replyToken ไว้ใช้งาน
    $replyToken = $events['events'][0]['replyToken'];
    $joinevent = $events['events'][0]['type'];
    $typeMessage = $events['events'][0]['message']['type'];
    $userMessage = strtolower($events['events'][0]['message']['text']);
   
    switch ($typeMessage){
        case 'text':
            switch ($userMessage) {
                case "i":
                    $arr_replyData = array();

                    $textReplyMessage   = "Bot ตอบกลับคุณเป็นข้อความ";
                    $arr_replyData[]    = new TextMessageBuilder($textReplyMessage);

                    $picFullSize        = 'https://www.google.co.th/imgres?imgurl=http://s3-ap-southeast-1.amazonaws.com/wpimage.shopspotapp.com/wp-content/uploads/2017/08/08120504/20480018_1123379674464780_8404745370706867972_n.jpg&imgrefurl=http://article.shopspotapp.com/ss-article/world-cat-day/&h=960&w=960&tbnid=ZH-_Yp7vp-fjFM:&tbnh=186&tbnw=186&usg=__LMSN7AXR_kTlJ8PmnUQtMkBeJ_w%3D&vet=10ahUKEwj-9qXL7azaAhWqHJoKHTehCf0Q_B0IgQIwDQ..i&docid=kgnsD3LLHtUTGM&itg=1&sa=X&ved=0ahUKEwj-9qXL7azaAhWqHJoKHTehCf0Q_B0IgQIwDQ';
                    $picThumbnail       = 'https://www.google.co.th/imgres?imgurl=http://s3-ap-southeast-1.amazonaws.com/wpimage.shopspotapp.com/wp-content/uploads/2017/08/08120504/20480018_1123379674464780_8404745370706867972_n.jpg&imgrefurl=http://article.shopspotapp.com/ss-article/world-cat-day/&h=960&w=960&tbnid=ZH-_Yp7vp-fjFM:&tbnh=186&tbnw=186&usg=__LMSN7AXR_kTlJ8PmnUQtMkBeJ_w%3D&vet=10ahUKEwj-9qXL7azaAhWqHJoKHTehCf0Q_B0IgQIwDQ..i&docid=kgnsD3LLHtUTGM&itg=1&sa=X&ved=0ahUKEwj-9qXL7azaAhWqHJoKHTehCf0Q_B0IgQIwDQ';
                    $arr_replyData[]    = new ImageMessageBuilder($picFullSize,$picThumbnail);

                    $placeName          = "ที่ตั้งร้าน";
                    $placeAddress       = "แขวง พลับพลา เขต วังทองหลาง กรุงเทพมหานคร ประเทศไทย";
                    $latitude           = 13.780401863217657;
                    $longitude          = 100.61141967773438;
                    $arr_replyData[]    = new LocationMessageBuilder($placeName, $placeAddress, $latitude ,$longitude);  
                    
                    $multiMessage       = new MultiMessageBuilder;
                    foreach($arr_replyData as $arr_Reply){
                            $multiMessage->add($arr_Reply);
                    }
                    $replyData = $multiMessage;                                     
                    break;
                    
                case "s":
                    $stickerID = 22;
                    $packageID = 2;
                    $replyData = new StickerMessageBuilder($packageID,$stickerID);
                    break;      
                // case "im":
                //     $imageMapUrl = 'https://www.mywebsite.com/imgsrc/photos/w/sampleimagemap';
                //     $replyData = new ImagemapMessageBuilder(
                //         $imageMapUrl,
                //         'This is Title',
                //         new BaseSizeBuilder(699,1040),
                //         array(
                //             new ImagemapMessageActionBuilder(
                //                 'test image map',
                //                 new AreaBuilder(0,0,520,699)
                //                 ),
                //             new ImagemapUriActionBuilder(
                //                 'http://www.ninenik.com',
                //                 new AreaBuilder(520,0,520,699)
                //                 )
                //         )); 
                //     break;          
                // case "tm":
                //     $replyData = new TemplateMessageBuilder('Confirm Template',
                //         new ConfirmTemplateBuilder(
                //                 'Confirm template builder',
                //                 array(
                //                     new MessageTemplateActionBuilder(
                //                         'Yes',
                //                         'Text Yes'
                //                     ),
                //                     new MessageTemplateActionBuilder(
                //                         'No',
                //                         'Text NO'
                //                     )
                //                 )
                //         )
                //     );
                //     break;                                                                                                                          
                default:
                    $textReplyMessage = " คุณไม่ได้พิมพ์ ค่า ตามที่กำหนด";
                    $replyData = new TextMessageBuilder($textReplyMessage);         
                    break;                                      
            }
            break;
        default:
            $textReplyMessage = json_encode($event);
            $replyData = new TextMessageBuilder($textReplyMessage);         
            break;  
    }
//l ส่วนของคำสั่งตอบกลับข้อความ


$response = $bot->replyMessage($replyToken , $replyData);
 
    if ($response->isSucceeded()) {
        echo 'Succeeded!';
        return;
    }

// Failed
echo $response->getHTTPStatus() . ' ' . $response->getRawBody();
 
}
?>