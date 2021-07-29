<div id="SECTION_SignUp">
    <div class="qtn qtn-V qtn-CntrH">
        <div class="Ctn Ctn-S Ctn-Clr-F Ctn-Brdr-UpDown Txt-IneritStl-Rcrsv">
            <div class="Grp-OnChld-L">
                <div class="Grp-OnChld-S qtn qtn-V qtn-CntrH">
                    <div class="qtn qtn-V qtn-CntrV Grp-OnChld-XXS">
                        <pre class="Txt-Sz-1-80 Txt-Algn-C">Inscription <i class="fas fa-user-plus"></i></pre>
                    </div>
                </div>
                <pre class="Grp-OnChld-XXS qtn qtn-V qtn-CntrH Txt-Sz-0-80">
                        <span>Cliquez sur <i class="fas fa-eye"></i> ou <i class="fas fa-eye-slash"></i></span>
                        <span>pour changer la confidentialité d'un champ</span>
                        <span>auprès des autres utilisateurs</span>
                    </pre>
                <div class="qtn qtn-V qtn-CntrH">
                    <form class="Grp-OnChld-L" action="inscription.php" method="post">
                        <div class="Grp-OnChld-XS">
                            <div class="qtn qtn-V Grp-OnChld-XXS">
                                <label id="SignUp_Username_Label" for="SignUp_Username_Input">Pseudo :</label>
                                <input id="SignUp_Username_Input" class="InputTxt InputTxt-L NoMarginSide" type="text" name="username" autocomplete="off" maxlength="<?php echo HTML_proof(USERNAME_CHAR_MAX)?>" value="<?php echo HTML_proof($_USER['username']); ?>">
                            </div>
                            <div class="qtn qtn-V Grp-OnChld-XXS">
                                <label id="SignUp_Email_Label" for="SignUp_Email_Input">Email :</label>
                                <div class="qtn qtn-H qtn-SzFtCntnt">
                                    <input id="SignUp_Email_Input" class="InputTxt InputTxt-XL NoMarginSide" type="email" name="email" autocomplete="off" maxlength="<?php echo HTML_proof(EMAIL_CHAR_MAX)?>" value="<?php echo  HTML_proof($_USER['email']); ?>">
                                    <input id="SignUp_Email_CheckBox" class="ChckBx-Vsbl ChckBx-StAt-R" type="checkbox" name="email-privacy" value="<?php echo HTML_proof(PRIVACY_PRIVATE);?>" <?php if($_USER['emailPrivacy']==1) echo "checked";?>>
                                </div>
                            </div>
                            <div class="qtn qtn-V qtn-CntrV Grp-OnChld-XXS">
                                <label id="SignUp_PhoneNumber_Label" for="SignUp_PhoneNumber_Input">Numéro de téléphone :</label>
                                <div class="qtn qtn-H qtn-SzFtCntnt">
                                    <input id="SignUp_PhoneNumber_Input" class="InputTxt InputTxt-L NoMarginSide" type="text" name="phoneNumber" autocomplete="off" maxlength="<?php echo HTML_proof(PHONENUMBER_CHAR_MAX)?>" value="<?php echo HTML_proof($_USER['phoneNumber']); ?>">
                                    <input id="SignUp_PhoneNumber_CheckBox" class="ChckBx-Vsbl ChckBx-StAt-R" type="checkbox" name="phoneNumber-privacy" value="<?php echo HTML_proof(PRIVACY_PRIVATE);?>" <?php if($_USER['phoneNumberPrivacy']===1) echo "checked";?>>
                                </div>
                            </div>
                            <div class="qtn qtn-V qtn-CntrV Grp-OnChld-XXS">
                                <label id="SignUp_City_Label" for="SignUp_City_Input">Ville :</label>
                                <div class="SlctSrch SlctSrch-XL">
                                    <input id="SignUp_City_Input" class="SlctSrch-Inpt NoMarginSide" type="search" name="city" autocomplete="off" value="<?php echo HTML_proof($_USER['city']); ?>">
                                    <div class="SlctSrch-Rslt NoMarginSide" data-index="">
                                        <?php foreach(getAllCityAndPostalCode() as $city):?>
                                            <div class="SlctSrch-Rslt-Elmnt"><?php echo HTML_proof($city[0])?> (<?php echo HTML_proof($city[1])?>)</div>
                                        <?php endforeach;?>
                                    </div>
                                    <input id="SignUp_City_CheckBox" class="ChckBx-Vsbl ChckBx-StAt-R" type="checkbox" name="city-privacy" value="<?php echo HTML_proof(PRIVACY_PRIVATE);?>" <?php if($_USER['cityPrivacy']==1) echo "checked";?>>
                                </div>
                            </div>
                        </div>
                        <div class="Grp-OnChld-XS">
                            <div class="qtn qtn-V Grp-OnChld-XXS">
                                <label id="SignUp_Password_Label" for="SignUp_Password_Input">Mot de passe :</label>
                                <input id="SignUp_Password_Input" class="InputTxt InputTxt-XL NoMarginSide" type="password" name="password" autocomplete="off" maxlength="<?php echo HTML_proof(PASSWORD_CHAR_MAX)?>">
                            </div>
                            <div class="qtn qtn-V Grp-OnChld-XXS">
                                <label id="SignUp_PasswordConf_Label" for="SignUp_PasswordConf_Input">Confirmation du mot de passe :</label>
                                <input id="SignUp_PasswordConf_Input" class="InputTxt InputTxt-XL NoMarginSide" type="password" name="passwordConf" autocomplete="off" maxlength="<?php echo HTML_proof(PASSWORD_CHAR_MAX)?>">
                            </div>
                        </div>
                        <div class="Grp-OnChld-XS qtn qtn-V qtn-CntrH qtn-SzInhrt">
                            <button id="SignUp_Submit" class="Btn Btn-Clr-S Btn-100 NoMarginSide Txt-Sz-1-20" name="signup-btn" type="submit">Inscription</button>
                            <pre class="Txt-Sz-0-75">Déjà inscrit ? <a href="connection.php" class="Txt-Clr-Spe-F Txt-Bld">Connectez-vous !</a></pre>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>