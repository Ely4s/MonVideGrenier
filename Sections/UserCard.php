<div id="SECTION_UserCard">
    <div class="qtn qtn-H qtn-CntrH">
        <div class="Ctn Ctn-M Ctn-Clr-F Ctn-Brdr-UpDown Ctn-NoPadding Txt-IneritStl-Rcrsv">
            <div class="qtn qtn-V">
                <div class="qtn qtn-H">
                    <div class="Ctn Ctn-W100 Ctn-NoShadow Ctn-NoPddngB">
                        <pre class="Txt-Sz-1-50 Txt-Algn-C">Mon Compte <i class="fas fa-user"></i></pre>
                    </div>
                </div>
                <div class="qtn qtn-H qtn-SpcBtw">
                    <div class="qtn qtn-H">
                        <div class="Ctn Ctn-UserCardLeft Ctn-NoPadding Ctn-NoShadow Ctn-NoPddngT">
                            <div class="qtn qtn-H qtn-CntrH qtn-SzInhrt">
                                <div class="PrflPic PrflPic-S">
                                    <img class="PrflPic-Elmnt" src="<?php echo FOLDERPATH_PROFILPICTURE.$_SESSION['profilPicture']; ?>">
                                </div>
                            </div>
                        </div>
                        <div class="Ctn Ctn-UserCardCenter Ctn-NoShadow Ctn-NoPadding">
                            <div class="qtn qtn-V qtn-SzInhrt qtn-SpcBtw Ctn Ctn-NoPddngL Ctn-NoShadow">
                                <div>
                                    <div class="Txt-Sz-1-40 Txt-Bld"><?php echo HTML_proof($_SESSION['username']);?></div>
                                </div>
                                <div class="Txt-Sz-1-00">
                                    <pre class="Txt-Inln <?php echo HTML_proof(getOpacityClassIfPrivateInfo("emailPrivacy"))?>">Email : <?php echo HTML_proof($_SESSION['email']);?> <?php echo getPrivateIconIfPrivateInfo("emailPrivacy")?></pre>
                                    <pre class="Txt-Inln <?php echo HTML_proof(getOpacityClassIfPrivateInfo("phoneNumberPrivacy"))?>">Tel : <?php echo HTML_proof($_SESSION['phoneNumber']);?> <?php echo getPrivateIconIfPrivateInfo("phoneNumberPrivacy")?></pre>
                                    <pre class="Txt-Inln <?php echo HTML_proof(getOpacityClassIfPrivateInfo("cityPrivacy"))?>">Ville : <?php echo HTML_proof($_SESSION['city']);?> <?php echo getPrivateIconIfPrivateInfo("cityPrivacy")?></pre>
                                </div>
                                <div class="Txt-Sz-0-90">
                                    <div><?php $user_ad_nbr = HTML_proof(getAdNbr_FromIdUser($_SESSION['id'])); echo $user_ad_nbr;?> annonce<?php echo_S_IfPlurial($user_ad_nbr);?> en ligne</div>
                                    <div><?php echo "Membre depuis ".HTML_proof(DateSinceArray_to_DateSinceStr(DateTimeStr_to_DateSinceArray($_SESSION['signupDatetime']), "", 2));?></div>
                                </div>
                                <div class="Txt-Sz-0-90 Txt-Clr-Spe-F">
                                    <a href="mon-compte.php?logout" class="logout">Se déconnecter</a></br>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="Wrppr-Btn-UsrMdfctin">
                <button id="SearchEngin_SearchKeyWord_Submit" class="Btn Btn-Clr-S Btn-Pddng-M NoMarginSide Txt-Sz-1-10" type="submit" onclick="location.href='modifier-mon-compte.php';"><pre>Modifier mes informations <i class="fas fa-user-edit"></i></pre></pre></button>
            </div>
        </div>
    </div>
</div>