<body marginheight="0" topmargin="0" marginwidth="0" style="margin: 0px; background-color: #f2f3f8;" leftmargin="0">
    <!--100% body table-->
    <table cellspacing="0" border="0" cellpadding="0" width="100%" bgcolor="#f2f3f8"
        style="@import url(https://fonts.googleapis.com/css?family=Rubik:300,400,500,700|Open+Sans:300,400,600,700); font-family: 'Open Sans', sans-serif;">
        <tr>
            <td>
                <table style="background-color: #f2f3f8; max-width:670px;  margin:0 auto;" width="100%" border="0"
                    align="center" cellpadding="0" cellspacing="0">
                    <tr>
                        <td style="height:50px;">&nbsp;</td>
                    </tr>
                    <tr>
                        <td style="text-align:center;">
                          <a href="https://www.indracompany.com/" title="Indra" style="margin-right: 10px; text-decoration: none;">
                            <img width="100" src="https://www.indracompany.com/sites/all/themes/custom/indracompany_bs/logo.png" title="Indra" alt="Indra">
                          </a>
                          <a href="https://www.enel.com/" title="Enel">
                            <img width="100" src="https://upload.wikimedia.org/wikipedia/commons/thumb/4/47/Enel_logo_2016.png/800px-Enel_logo_2016.png" title="Enel" alt="Enel">
                          </a>
                        </td>
                    </tr>
                    <tr>
                        <td style="height:20px;">&nbsp;</td>
                    </tr>
                    <tr>
                        <td>
                            <table width="95%" border="0" align="center" cellpadding="0" cellspacing="0"
                                style="max-width:670px;background:#fff; border-radius:3px; text-align:center;-webkit-box-shadow:0 6px 18px 0 rgba(0,0,0,.06);-moz-box-shadow:0 6px 18px 0 rgba(0,0,0,.06);box-shadow:0 6px 18px 0 rgba(0,0,0,.06);">
                                <tr>
                                    <td style="height:40px;">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td style="padding:0 35px;">
                                        <h1 style="color:#1e1e2d; font-weight:500; margin:0;font-size:32px;font-family:'Rubik',sans-serif;">Usted ha 
                                        solicitado restablecer su contraseña</h1>
                                        <span
                                            style="display:inline-block; vertical-align:middle; margin:29px 0 26px; border-bottom:1px solid #cecece; width:100px;"></span>
                                        <p style="color:#455056; font-size:15px;line-height:24px; margin:0;">
                                            Para cambiar su contraseña en las aplicaciones de Mantenedores e Hidrología
                                            hemos generado un enlace para usted. Por favor, haga clic en el botón de abajo
                                            y siga las instrucciones.
                                        </p>
                                        <a href="<?php echo e($link = url('password/reset', $token).'?email='.urlencode($user->getEmailForPasswordReset())); ?>"
                                            style="background:#337ab7;text-decoration:none !important; font-weight:500; margin-top:35px; color:#fff;text-transform:uppercase; font-size:14px;padding:10px 24px;display:inline-block;border-radius:50px;">Restablecer contraseña</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="height:40px;">&nbsp;</td>
                                </tr>
                            </table>
                        </td>
                    <tr>
                        <td style="height:20px;">&nbsp;</td>
                    </tr>                    
                </table>
            </td>
        </tr>
    </table>
    <!--/100% body table-->
</body><?php /**PATH D:\BDComercial\Hidrologia_Site\resources\views\auth\emails\password.blade.php ENDPATH**/ ?>