<div id="SECTION_UserUpdate">
    <div class="qtn qtn-V qtn-CntrH">
        <div class="Ctn Ctn-S Ctn-Clr-F Ctn-Brdr-UpDown Txt-IneritStl-Rcrsv">
            <div class="Grp-OnChld-L">
                <div class="Grp-OnChld-S qtn qtn-V qtn-CntrH">
                    <div class="qtn qtn-V qtn-CntrV Grp-OnChld-XXS">
                        <pre class="Txt-Sz-1-80 Txt-Algn-C">Modifier mes informations <i class="fas fa-user-edit"></i></pre>
                    </div>
                </div>
                <pre class="Grp-OnChld-XXS qtn qtn-V qtn-CntrH Txt-Sz-0-80">
                            <span>Laissez un champ vide où tel quel</span>
                            <span>pour ne pas le modifier</span>
                        </pre>
                <div class="qtn qtn-V qtn-CntrH">
                    <form class="Grp-OnChld-L" action="modifier-mon-compte.php" method="post" enctype="multipart/form-data">
                        <div class="Grp-OnChld-XS">
                            <div class="Grp-OnSlf-L qtn qtn-H qtn-CntrH">
                                <div class="Chng-PrflPic">
                                    <label id="UserUpdate_profilPicture_Label" class="Chng-PrflPic-Elmnt" for="UserUpdate_profilPicture_Input">
                                        <div class="PrflPic PrflPic-S"><img class="PrflPic-Elmnt" src="<?php echo FOLDERPATH_PROFILPICTURE.$_SESSION['profilPicture']?>"></div>
                                        <i class="fas fa-upload Txt-Sz-2-00"></i>
                                        <input id="UserUpdate_profilPicture_Input" type="file" name="profilPicture" accept="image/png, image/jpeg">
                                    </label>
                                </div>
                            </div>
                            <div class="qtn qtn-V Grp-OnChld-XXS">
                                <label id="UserUpdate_Email_Label" for="UserUpdate_Email_Input">Email :</label>
                                <div class="qtn qtn-H qtn-SzFtCntnt">
                                    <input id="UserUpdate_Email_Input" class="InputTxt InputTxt-XL NoMarginSide" type="email" name="email" autocomplete="off" maxlength="<?php echo HTML_proof(EMAIL_CHAR_MAX)?>" value="<?php echo HTML_proof(get_whichExist([$_USER['email'],$_SESSION['email']])); ?>">
                                    <input id="UserUpdate_Email_CheckBox" class="ChckBx-Vsbl ChckBx-StAt-R" type="checkbox" name="email-privacy" value="<?php echo HTML_proof(PRIVACY_PRIVATE);?>" <?php if(get_whichExist([$_USER['emailPrivacy'],$_SESSION['emailPrivacy']]) == 1) echo "checked";?>>
                                </div>
                            </div>
                            <div class="qtn qtn-V qtn-CntrV Grp-OnChld-XXS">
                                <label id="UserUpdate_PhoneNumber_Label" for="UserUpdate_PhoneNumber_Input">Numéro de téléphone :</label>
                                <div class="qtn qtn-H qtn-SzFtCntnt">
                                    <input id="UserUpdate_PhoneNumber_Input" class="InputTxt InputTxt-L NoMarginSide" type="text" name="phoneNumber" autocomplete="off" maxlength="<?php echo HTML_proof(PHONENUMBER_CHAR_MAX)?>" value="<?php echo HTML_proof(get_whichExist([$_USER['phoneNumber'],$_SESSION['phoneNumber']])); ?>">
                                    <input id="UserUpdate_PhoneNumber_CheckBox" class="ChckBx-Vsbl ChckBx-StAt-R" type="checkbox" name="phoneNumber-privacy" value="<?php echo HTML_proof(PRIVACY_PRIVATE);?>" <?php if(get_whichExist([$_USER['phoneNumberPrivacy'],$_SESSION['phoneNumberPrivacy']]) == 1) echo "checked";?>>
                                </div>
                            </div>
                            <div class="qtn qtn-V qtn-CntrV Grp-OnChld-XXS">
                                <label id="UserUpdate_City_Label" for="UserUpdate_City_Input">Ville :</label>
                                <div class="SlctSrch SlctSrch-XL">
                                    <input id="UserUpdate_City_Input" class="SlctSrch-Inpt NoMarginSide" type="search" name="city" autocomplete="off" maxlength="<?php echo HTML_proof(CITY_CHAR_MAX)?>" value="<?php echo HTML_proof(get_whichExist([$_USER['city'],$_SESSION['city']]) ); ?>">
                                    <div class="SlctSrch-Rslt NoMarginSide" data-index="">
                                        <?php foreach(getAllCityAndPostalCode() as $city):?>
                                            <div class="SlctSrch-Rslt-Elmnt"><?php echo HTML_proof($city[0])?> (<?php echo HTML_proof($city[1])?>)</div>
                                        <?php endforeach;?>
                                    </div>
                                    <input id="UserUpdate_City_CheckBox" class="ChckBx-Vsbl ChckBx-StAt-R" type="checkbox" name="city-privacy" value="<?php echo HTML_proof(PRIVACY_PRIVATE);?>" <?php if(get_whichExist([$_USER['cityPrivacy'],$_SESSION['cityPrivacy']]) == 1) echo "checked";?>>
                                </div>
                            </div>
                        </div>
                        <div class="Grp-OnChld-XS">
                            <div class="qtn qtn-V Grp-OnChld-XXS">
                                <label id="UserUpdate_PasswordOld_Label" for="UserUpdate_PasswordOld_Input">Ancien mot de passe :</label>
                                <input id="UserUpdate_PasswordOld_Input" class="InputTxt InputTxt-XL NoMarginSide" type="password" name="password" autocomplete="off">
                            </div>
                            <div class="qtn qtn-V Grp-OnChld-XXS">
                                <label id="UserUpdate_Password_Label" for="UserUpdate_Password_Input">Nouveau mot de passe :</label>
                                <input id="UserUpdate_Password_Input" class="InputTxt InputTxt-XL NoMarginSide" type="password" name="passwordNew" autocomplete="off" maxlength="<?php echo HTML_proof(PASSWORD_CHAR_MAX)?>">
                            </div>
                            <div class="qtn qtn-V Grp-OnChld-XXS">
                                <label id="UserUpdate_PasswordConf_Label" for="UserUpdate_PasswordConf_Input">Confirmation du nouveau mot de passe :</label>
                                <input id="UserUpdate_PasswordConf_Input" class="InputTxt InputTxt-XL NoMarginSide" type="password" name="passwordNewConf" autocomplete="off" maxlength="<?php echo HTML_proof(PASSWORD_CHAR_MAX)?>">
                            </div>
                        </div>
                        <div class="Grp-OnChld-XS qtn qtn-V qtn-CntrH qtn-SzInhrt">
                            <button id="UserUpdate_Submit" class="Btn Btn-Clr-S Btn-100 NoMarginSide Txt-Sz-1-20" name="userInfoModify-btn" type="submit">Modifier</button>
                            <pre class="Txt-Sz-0-75"><a href="mon-compte.php" class="Txt-Clr-Spe-F Txt-Bld">Annuler et retourner sur mon compte</a></pre>
                        </div>
                    </form>
                </div>
                <div class="qtn qtn-V qtn-CntrH">
                    <button id="AdUpdate_Delete" class="Btn Btn-Clr-S Btn Btn-Pddng-S NoMarginSide Txt-Sz-1-00 Btn-Cnfrm-Off" name="adModify-btn" data-lnk="modifier-mon-compte.php?delete" data-cnfrm-txt="Confirmer la suppression">Supprimer mon compte</button>
                </div>
            </div>
        </div>
    </div>
</div>
