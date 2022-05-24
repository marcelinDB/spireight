<?php
// src/Controller/MailerController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class MailerController extends AbstractController
{
    private $header = "<!DOCTYPE HTML PUBLIC '-//W3C//DTD XHTML 1.0 Transitional //EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
    <html xmlns='http://www.w3.org/1999/xhtml' xmlns:v='urn:schemas-microsoft-com:vml' xmlns:o='urn:schemas-microsoft-com:office:office'>
    <head>
    <!--[if gte mso 9]>
    <xml>
      <o:OfficeDocumentSettings>
        <o:AllowPNG/>
        <o:PixelsPerInch>96</o:PixelsPerInch>
      </o:OfficeDocumentSettings>
    </xml>
    <![endif]-->
      <meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
      <meta name='viewport' content='width=device-width, initial-scale=1.0'>
      <meta name='x-apple-disable-message-reformatting'>
      <!--[if !mso]><!--><meta http-equiv='X-UA-Compatible' content='IE=edge'><!--<![endif]-->
      <title></title>
      
        <style type='text/css'>
          table, td { color: #000000; } a { color: #0e0e27; text-decoration: none; } @media (max-width: 480px) { #u_content_image_1 .v-container-padding-padding { padding: 0px !important; } #u_content_image_1 .v-src-width { width: 2880px !important; } #u_content_image_1 .v-src-max-width { max-width: 42% !important; } #u_column_21 .v-col-background-color { background-color: #000000 !important; } }
    @media only screen and (min-width: 670px) {
      .u-row {
        width: 650px !important;
      }
      .u-row .u-col {
        vertical-align: top;
      }
    
      .u-row .u-col-33p33 {
        width: 216.645px !important;
      }
    
      .u-row .u-col-100 {
        width: 650px !important;
      }
    
    }
    
    @media (max-width: 670px) {
      .u-row-container {
        max-width: 100% !important;
        padding-left: 0px !important;
        padding-right: 0px !important;
      }
      .u-row .u-col {
        min-width: 320px !important;
        max-width: 100% !important;
        display: block !important;
      }
      .u-row {
        width: calc(100% - 40px) !important;
      }
      .u-col {
        width: 100% !important;
      }
      .u-col > div {
        margin: 0 auto;
      }
    }
    body {
      margin: 0;
      padding: 0;
    }
    
    table,
    tr,
    td {
      vertical-align: top;
      border-collapse: collapse;
    }
    
    .ie-container table,
    .mso-container table {
      table-layout: fixed;
    }
    
    * {
      line-height: inherit;
    }
    
    a[x-apple-data-detectors='true'] {
      color: inherit !important;
      text-decoration: none !important;
    }
    
    </style>
      
      
    
    <!--[if !mso]><!--><link href='https://fonts.googleapis.com/css?family=Montserrat:400,700&display=swap' rel='stylesheet' type='text/css'><!--<![endif]-->
    
    </head>
    
    <body class='clean-body u_body' style='margin: 0;padding: 0;-webkit-text-size-adjust: 100%;background-color: #a3a3a3;color: #000000' bgcolor='#a3a3a3'>
      <table cellspacing='0' cellpadding='0' border='0' width='700px' style='margin: auto;'>
    
        <tr style='background-color: rgb(0, 0, 0);'>
        
        <td style='align-content: center;align-items: center;' align='center'>
        
          <img align='center' border='0' src='https://pixel4d.fr/wp-content/uploads/2020/11/pixel4d-logo.png' alt='LogoPixel4d' title='Image' style='outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;clear: both;display: inline-block !important;border: none;height: auto;float: none;width: 20%;max-width: 129.6px;' width='150' class='v-src-width v-src-max-width'/>
        
        </td>
        
        </tr>
    
        <tr align='center' style='background-color: white;'>
          <td style='margin:auto;'>
            <table style='width: 600px;background-color: rgb(255, 255, 255);' width='600'>
              <tr style='height: 20px;'></tr>
              <tr>
                <td style='word-break: break-all;'>
                    <style>code{background-color:gray;}</style>
                  <!--DEBUT-->

                  <!--FIN-->
                ";
        private $footer = "</td>
              </tr>
              <tr style='height: 20px;'></tr>
            </table>
          </td>
        </tr>
    
        <tr style='background-color: rgb(0, 0, 0);color: #5298de;text-transform: uppercase;' align='center'>
          <td style='width: 100%;color: #5298de;'>
            <h3 style='color:white;padding: 0px 20px;margin: 20px;font-weight: bold;font-family:'Montserrat',sans-serif;margin-top: 45px;'>CONTACT</h3>
            <h4 style='width: 100%;margin-top:0;color: #5298de;font-weight: bold;font-family:'Montserrat',sans-serif;'> contact@pixel4d.fr </h4>
            <h4 style='width: 100%;margin-top:0;color: #5298de;font-weight: bold;font-family:'Montserrat',sans-serif;'>03 51 25 55 55</h4>
          </td>
        </tr>
        <tr style='background-color: rgb(0, 0, 0);color: #5298de;' align='center'>
          <td style='width: 100%;text-transform: uppercase;'>
            <a href='https://pixel4d.fr'><img src='https://pixel4d.fr/wp-content/uploads/2020/11/pixel4d-logo.png' alt='logo Pixel4d' width='150' alt='Pixel4d'></a>
            <h4 style='width: 100%;margin-top:0;color: #5298de;font-weight: bold;font-family:'Montserrat',sans-serif;'>8 RUE DE L'ARTISANT</h4>
            <h4 style='width: 100%;margin-top:0;color: #5298de;font-weight: bold;font-family:'Montserrat',sans-serif;'>08000 Charleville-Mézières</h4>
          </td>
        </tr>
        <tr style='background-color: rgb(0, 0, 0);color: #5298de;text-transform: uppercase;' align='center'>
          <td style='width: 100%;'>
            <h3 style=' color:white;padding: 20px;margin:auto;margin: 20px;font-weight: bold;font-family:'Montserrat',sans-serif;'>SUIVEZ-NOUS</h3>
            <a href='https://www.facebook.com/pixel4d.vr%20' style='text-decoration: none;'><img src='https://pixel4d.fr/wp-content/uploads/2021/11/facebook.png' alt='facebook' height='50' style='display: inline-block;list-style: none;height: 50px;'></a>
            <a href='https://twitter.com/pixel4d?lang=fr' style='text-decoration: none;'><img src='https://pixel4d.fr/wp-content/uploads/2021/11/twitter.png' alt='twitter' height='50' style='display: inline-block;list-style: none;height: 50px;margin-left: 3px;'></a>
            <a href='https://www.instagram.com/pixel_4d/' style='text-decoration: none;'><img src='https://pixel4d.fr/wp-content/uploads/2021/11/Instagram.png' alt='instagram' height='50' style='display: inline-block;list-style: none;height: 50px;margin-left: 3px;'></a>
            <a href='https://www.linkedin.com/authwall?trk=gf&trkInfo=AQGpfdJUJeJ0kAAAAX0uV7ooyK2x-AZGo0YgdrAqt5jZpjO68-l3Q4IktIm65xxZpRfmuZDOAIPlGcbFCT_-LL0YQA1IGyOZggJRWlB-865n7PyScaahD9EmnVqrGV_ioY_3-qo=&originalReferer=https://pixel4d.fr/&sessionRedirect=https%3A%2F%2Ffr.linkedin.com%2Fcompany%2Fpixel-4d%3Ftrk%3Dpublic_profile_experience-item_result-card_image-click%2520' style='text-decoration: none;margin-left: 3px;'><img src='https://pixel4d.fr/wp-content/uploads/2021/11/Linkedin.png' alt='linkIn' height='50' style='display: inline-block;list-style: none;height: 50px;'></a>
          </td>
        </tr>
        <tr style='background-color: rgb(0, 0, 0);text-transform: uppercase;font-weight: bold;' align='center'>
          <td>
            <h5 style='color: white;font-weight: none;padding-top: 20px;font-weight: bold;font-family:'Montserrat',sans-serif;margin-top: 20px;'>ⒸCopyright 2021 | <a href='https://pixel4d.fr/mentions-legales/' style='color: #5298de;'> Tous droits réservés </a> | <a href='https://pixel4d.fr/conditions-generales-de-ventes/' style='color: #5298de;'> Conditions d’utilisation </a>.</h5>
          </td>
        </tr>
        
      </table>
    
    </body>
    
    </html>";


    #[Route('/email')]
    public function sendEmail(MailerInterface $mailer,string $sujet,string $destinataire,string $content):Response
    {
       /* $email = (new Email())
        ->from('marcod.production@gmail.com')
        ->to('local@orange.fr')
        ->subject('Time for Symfony Mailer!')
        ->html($this->header.$this->footer);

        try {
            $mailer->send($email);
        } catch (\Throwable $th) {
            dd($th);
        }*/

        try {
          $sujet = utf8_decode($sujet);
          // L'en-tête Mail "Content-type" doit être défini :
          $headers  = "MIME-Version: 1.0 \r\n";
          $headers .= "Content-type: text/html; charset=UTF-8 \r\n";
          // En-têtes additionnels :
          $headers .= "To: "."contact@^pixel4d.fr"."\r\n";     
          $headers .= "From: ".$destinataire."\r\n";
          // Envoi du mail
          mail($destinataire, $sujet, $this->header.$content.$this->footer, $headers);
    
        } catch (\Throwable $th) {
          //throw $th;
        }

        return new Response('Sent email !');
    }
}