<?php

namespace App\Console\Commands;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\TokenReceived;
use App\Mail\EmailIngresos;
use App\Models\Country;
use App\Models\EMail;
use App\Models\Ingreso;
use App\Models\User;
use App\Models\Lote;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;


use DNS2D;
use Illuminate\Support\Str;

class EnviarToken extends Command
{


    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Token:Enviar';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {




        try {
                    $email =  EMail::where('ml_envio','=',0)->where('ml_tipo',"=",1)->take(10)->get();



                        $now =\Carbon\Carbon::now('America/Argentina/Buenos_Aires')->addHours(2);

                                foreach($email as $emailValue){

                              $auth_token = User::geToken();

                                $input = [
                                'password' =>Hash::make($auth_token)
                                ,'us_updated_token'=>$now];

                                $user = User::where('email','=',$emailValue->ml_user_email)->where('us_active',"=",1) ->first();

                              $lote = Lote::where("lot_id","=",$user->us_lote_id)->first();

                                $country = Country::where('co_id',"=",$lote->lot_country_id)->first();

                                $user->update($input);

                                $email = new \SendGrid\Mail\Mail();
                                $email->setFrom("noreply@seguridadunica.com", $country->co_name);
                                $email->setSubject("Generacion de Token");
                                $email->addTo($emailValue->ml_user_email, "");

                                $email->addContent(
                                    "text/html", "<!doctype html>
                                    <html>
                                      <head>
                                        <meta name='viewport' content='width=device-width, initial-scale=1.0'/>
                                        <meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
                                        <title>Simple Transactional Email</title>
                                        <style>
                                          /* -------------------------------------
                                              GLOBAL RESETS
                                          ------------------------------------- */

                                          /*All the styling goes here*/

                                          img {
                                            border: none;
                                            -ms-interpolation-mode: bicubic;
                                            max-width: 100%;
                                          }

                                          body {
                                            background-color: #f6f6f6;
                                            font-family: sans-serif;
                                            -webkit-font-smoothing: antialiased;
                                            font-size: 14px;
                                            line-height: 1.4;
                                            margin: 0;
                                            padding: 0;
                                            -ms-text-size-adjust: 100%;
                                            -webkit-text-size-adjust: 100%;
                                          }

                                          table {
                                            border-collapse: separate;
                                            mso-table-lspace: 0pt;
                                            mso-table-rspace: 0pt;
                                            width: 100%; }
                                            table td {
                                              font-family: sans-serif;
                                              font-size: 14px;
                                              vertical-align: top;
                                          }

                                          /* -------------------------------------
                                              BODY & CONTAINER
                                          ------------------------------------- */

                                          .body {
                                            background-color: #f6f6f6;
                                            width: 100%;
                                          }

                                          /* Set a max-width, and make it display as block so it will automatically stretch to that width, but will also shrink down on a phone or something */
                                          .container {
                                            display: block;
                                            margin: 0 auto !important;
                                            /* makes it centered */
                                            max-width: 580px;
                                            padding: 10px;
                                            width: 580px;
                                          }

                                          /* This should also be a block element, so that it will fill 100% of the .container */
                                          .content {
                                            box-sizing: border-box;
                                            display: block;
                                            margin: 0 auto;
                                            max-width: 580px;
                                            padding: 10px;
                                          }

                                          /* -------------------------------------
                                              HEADER, FOOTER, MAIN
                                          ------------------------------------- */
                                          .main {
                                            background: #ffffff;
                                            border-radius: 3px;
                                            width: 100%;
                                          }

                                          .wrapper {
                                            box-sizing: border-box;
                                            padding: 20px;
                                          }

                                          .content-block {
                                            padding-bottom: 10px;
                                            padding-top: 10px;
                                          }

                                          .footer {
                                            clear: both;
                                            margin-top: 10px;
                                            text-align: center;
                                            width: 100%;
                                          }
                                            .footer td,
                                            .footer p,
                                            .footer span,
                                            .footer a {
                                              color: #999999;
                                              font-size: 12px;
                                              text-align: center;
                                          }

                                          /* -------------------------------------
                                              TYPOGRAPHY
                                          ------------------------------------- */
                                          h1,
                                          h2,
                                          h3,
                                          h4 {
                                            color: #000000;
                                            font-family: sans-serif;
                                            font-weight: 400;
                                            line-height: 1.4;
                                            margin: 0;
                                            margin-bottom: 30px;
                                          }

                                          h1 {
                                            font-size: 35px;
                                            font-weight: 300;
                                            text-align: center;
                                            text-transform: capitalize;
                                          }

                                          p,
                                          ul,
                                          ol {
                                            font-family: sans-serif;
                                            font-size: 14px;
                                            font-weight: normal;
                                            margin: 0;
                                            margin-bottom: 15px;
                                          }
                                            p li,
                                            ul li,
                                            ol li {
                                              list-style-position: inside;
                                              margin-left: 5px;
                                          }

                                          a {
                                            color: #3498db;
                                            text-decoration: underline;
                                          }

                                          /* -------------------------------------
                                              BUTTONS
                                          ------------------------------------- */
                                          .btn {
                                            box-sizing: border-box;
                                            width: 100%; }
                                            .btn > tbody > tr > td {
                                              padding-bottom: 15px; }
                                            .btn table {
                                              width: auto;
                                          }
                                            .btn table td {
                                              background-color: #ffffff;
                                              border-radius: 5px;
                                              text-align: center;
                                          }
                                            .btn a {
                                              background-color: #ffffff;
                                              border: solid 1px #3498db;
                                              border-radius: 5px;
                                              box-sizing: border-box;
                                              color: #3498db;
                                              cursor: pointer;
                                              display: inline-block;
                                              font-size: 14px;
                                              font-weight: bold;
                                              margin: 0;
                                              padding: 12px 25px;
                                              text-decoration: none;
                                              text-transform: capitalize;
                                          }

                                          .btn-primary table td {
                                            background-color: #3498db;
                                          }

                                          .btn-primary a {
                                            background-color: #3498db;
                                            border-color: #3498db;
                                            color: #ffffff;
                                          }

                                          /* -------------------------------------
                                              OTHER STYLES THAT MIGHT BE USEFUL
                                          ------------------------------------- */
                                          .last {
                                            margin-bottom: 0;
                                          }

                                          .first {
                                            margin-top: 0;
                                          }

                                          .align-center {
                                            text-align: center;
                                          }

                                          .align-right {
                                            text-align: right;
                                          }

                                          .align-left {
                                            text-align: left;
                                          }

                                          .clear {
                                            clear: both;
                                          }

                                          .mt0 {
                                            margin-top: 0;
                                          }

                                          .mb0 {
                                            margin-bottom: 0;
                                          }

                                          .preheader {
                                            color: transparent;
                                            display: none;
                                            height: 0;
                                            max-height: 0;
                                            max-width: 0;
                                            opacity: 0;
                                            overflow: hidden;
                                            mso-hide: all;
                                            visibility: hidden;
                                            width: 0;
                                          }

                                          .powered-by a {
                                            text-decoration: none;
                                          }

                                          hr {
                                            border: 0;
                                            border-bottom: 1px solid #f6f6f6;
                                            margin: 20px 0;
                                          }

                                          /* -------------------------------------
                                              RESPONSIVE AND MOBILE FRIENDLY STYLES
                                          ------------------------------------- */
                                          @media only screen and (max-width: 620px) {
                                            table.body h1 {
                                              font-size: 28px !important;
                                              margin-bottom: 10px !important;
                                            }
                                            table.body p,
                                            table.body ul,
                                            table.body ol,
                                            table.body td,
                                            table.body span,
                                            table.body a {
                                              font-size: 16px !important;
                                            }
                                            table.body .wrapper,
                                            table.body .article {
                                              padding: 10px !important;
                                            }
                                            table.body .content {
                                              padding: 0 !important;
                                            }
                                            table.body .container {
                                              padding: 0 !important;
                                              width: 100% !important;
                                            }
                                            table.body .main {
                                              border-left-width: 0 !important;
                                              border-radius: 0 !important;
                                              border-right-width: 0 !important;
                                            }
                                            table.body .btn table {
                                              width: 100% !important;
                                            }
                                            table.body .btn a {
                                              width: 100% !important;
                                            }
                                            table.body .img-responsive {
                                              height: auto !important;
                                              max-width: 100% !important;
                                              width: auto !important;
                                            }
                                          }

                                          /* -------------------------------------
                                              PRESERVE THESE STYLES IN THE HEAD
                                          ------------------------------------- */
                                          @media all {
                                            .ExternalClass {
                                              width: 100%;
                                            }
                                            .ExternalClass,
                                            .ExternalClass p,
                                            .ExternalClass span,
                                            .ExternalClass font,
                                            .ExternalClass td,
                                            .ExternalClass div {
                                              line-height: 100%;
                                            }
                                            .apple-link a {
                                              color: inherit !important;
                                              font-family: inherit !important;
                                              font-size: inherit !important;
                                              font-weight: inherit !important;
                                              line-height: inherit !important;
                                              text-decoration: none !important;
                                            }
                                            #MessageViewBody a {
                                              color: inherit;
                                              text-decoration: none;
                                              font-size: inherit;
                                              font-family: inherit;
                                              font-weight: inherit;
                                              line-height: inherit;
                                            }
                                            .btn-primary table td:hover {
                                              background-color: #34495e !important;
                                            }
                                            .btn-primary a:hover {
                                              background-color: #34495e !important;
                                              border-color: #34495e !important;
                                            }
                                          }

                                        </style>
                                      </head>
                                      <body>

                                        <table role='presentation' border='0' cellpadding='0' cellspacing='0' class='body'>
                                          <tr>
                                            <td>&nbsp;</td>
                                            <td class='container'>
                                              <div class='content'>

                                                <!-- START CENTERED WHITE CONTAINER -->
                                                <table role='presentation' class='main'>

                                                  <!-- START MAIN CONTENT AREA -->
                                                  <tr>
                                                    <td class='wrapper'>
                                                      <table role='presentation' border='0' cellpadding='0' cellspacing='0'>
                                                        <tr>
                                                          <td>
                                                            <p>Hola, ".$user->us_name.",</p>
                                                            <p>Haz generado un token para el ingreso a la APP de servicios. </p>
                                                            <p>Su nuevo token es: </p>
                                                            <table role='presentation' border='0' cellpadding='0' cellspacing='0' class='btn btn-primary'>
                                                              <tbody>
                                                                <tr>
                                                                  <td align='left'>
                                                                    <table role='presentation' border='0' cellpadding='0' cellspacing='0'>
                                                                      <tbody>
                                                                        <tr>
                                                                          <td> <font size='5'>  ".    $auth_token ."</font> </td>
                                                                        </tr>
                                                                      </tbody>
                                                                    </table>
                                                                  </td>
                                                                </tr>
                                                              </tbody>
                                                            </table>
                                                            <p>Gracias por su confianza!</p>
                                                          </td>
                                                        </tr>
                                                      </table>
                                                    </td>
                                                  </tr>

                                                <!-- END MAIN CONTENT AREA -->
                                                </table>
                                                <!-- END CENTERED WHITE CONTAINER -->

                                                <!-- START FOOTER -->
                                                <div class='footer'>
                                                  <table role='presentation' border='0' cellpadding='0' cellspacing='0'>
                                                    <tr>
                                                      <td class='content-block'>
                                                      <font size='5' color='red'> Si usted no reconoce esta acción, por favor comunicarse con la Administración.</font>
                                                      </td>
                                                    </tr>

                                                  </table>
                                                </div>
                                                <!-- END FOOTER -->

                                              </div>
                                            </td>
                                            <td>&nbsp;</td>
                                          </tr>
                                        </table>
                                      </body>
                                    </html>"
                                );
                                $sendgrid = new \SendGrid(env('SENDGRID_API_KEY'), ['host' => env('SENDGRID_HOST_TLS_12')]);
                                try {
                                    $response = $sendgrid->send($email);
                                    EMail::where('ml_id','=',$emailValue->ml_id)->update(['ml_envio'=>1]);
                               /*      print $response->statusCode() . "\n";
                                    print_r($response->headers());
                                    print $response->body() . "\n"; */
                                } catch (Exception $e) {
                                    echo 'Caught exception: '. $e->getMessage() ."\n";
                                }

                              //  Mail::to($user->email)->send(new TokenReceived($country,$auth_token));



                                }
                        } catch (\Throwable $th) {

                            \Log::error($th);
                        }

    }
}
