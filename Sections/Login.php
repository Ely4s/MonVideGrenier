<div id="SECTION_Login">
    <div class="qtn qtn-V qtn-CntrH">
        <div class="Ctn Ctn-S Ctn-Clr-F Ctn-Brdr-UpDown Txt-IneritStl-Rcrsv">
            <div class="Grp-OnChld-L">
                <div class="Grp-OnChld-S qtn qtn-V qtn-CntrH">
                    <div class="qtn qtn-V qtn-CntrV Grp-OnChld-XXS">
                        <pre class="Txt-Sz-1-80 Txt-Algn-C">Connection <i class="fas fa-sign-in-alt"></i></pre>
                    </div>
                </div>
                <div class="qtn qtn-V qtn-CntrH">
                    <form class="Grp-OnChld-L" action="connection.php" method="post">
                        <div class="Grp-OnChld-XS">
                            <div class="qtn qtn-V Grp-OnChld-XXS">
                                <label id="Login_UsernameEmail_Label" for="Login_UsernameEmail_Input">Pseudo ou Email :</label>
                                <input id="Login_UsernameEmail_Input" class="InputTxt InputTxt-XL NoMarginSide" type="text" name="username" autocomplete="off" value="<?php if(isset($_USER['username'])){echo HTML_proof($_USER['username']);}?>">
                            </div>
                        </div>
                        <div class="Grp-OnChld-XS">
                            <div class="qtn qtn-V Grp-OnChld-XXS">
                                <label id="Login_Password_Label" for="Login_Password_Input">Mot de passe :</label>
                                <input id="Login_Password_Input" class="InputTxt InputTxt-XL NoMarginSide" type="password" name="password" autocomplete="off" placeholder="">
                            </div>
                        </div>
                        <div class="Grp-OnChld-XS qtn qtn-V qtn-CntrH qtn-SzInhrt">
                            <button id="Login_Submit" class="Btn Btn-Clr-S Btn-100 NoMarginSide Txt-Sz-1-20" type="submit" name="login-btn">Connection</button>
                            <pre class="Txt-Sz-0-75">Pas encore inscrit ? <a href="inscription.php" class="Txt-Clr-Spe-F Txt-Bld">Inscrivez-vous !</a></pre>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
