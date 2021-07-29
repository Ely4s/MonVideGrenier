<div id="SECTION_SellerCard">
    <div class="qtn qtn-H qtn-CntrH">
        <div class="Ctn Ctn-M Ctn-Clr-F Ctn-Brdr-UpDown Ctn-NoPadding Txt-IneritStl-Rcrsv">
            <div class="qtn qtn-V">
                <div class="qtn qtn-H">
                    <div class="Ctn Ctn-W100 Ctn-NoShadow Ctn-NoPddngB">
                        <pre class="Txt-Sz-1-50 Txt-Algn-C">Profile <i class="fas fa-address-card"></i></pre>
                    </div>
                </div>
                <div class="qtn qtn-H qtn-SpcBtw">
                    <div class="qtn qtn-H">
                        <div class="Ctn Ctn-SellerCardLeft Ctn-NoPadding Ctn-NoShadow Ctn-NoPddngT">
                            <div class="qtn qtn-H qtn-CntrH qtn-SzInhrt">
                                <div class="PrflPic PrflPic-S">
                                    <img class="PrflPic-Elmnt" src="<?php echo FOLDERPATH_PROFILPICTURE.$_USER['USERS_profilPicturePath']; ?>">
                                </div>
                            </div>
                        </div>
                        <div class="Ctn Ctn-SellerCardCenter Ctn-NoShadow Ctn-NoPadding">
                            <div class="qtn qtn-V qtn-SzInhrt qtn-SpcBtw Ctn Ctn-NoPddngL Ctn-NoShadow">
                                <div>
                                    <div class="Txt-Sz-1-40 Txt-Bld"><?php echo HTML_proof($_USER['USERS_username']);?></div>
                                </div>
                                <div class="Txt-Sz-1-00">
                                    <?php if($_USER['USERS_emailPrivacy'] === 0):?>
                                    <pre class="Txt-Inln">Email : <?php echo HTML_proof($_USER['USERS_email']);?></pre>
                                    <?php endif;?>
                                    <?php if($_USER['USERS_phoneNumberPrivacy'] === 0):?>
                                    <pre class="Txt-Inln">Tel : <?php echo HTML_proof($_USER['USERS_phoneNumber']);?></pre>
                                    <?php endif;?>
                                    <?php if($_USER['USERS_cityPrivacy'] === 0):?>
                                    <pre class="Txt-Inln">Ville : <?php echo HTML_proof($_USER['USERS_city']);?></pre>
                                    <?php endif;?>
                                </div>
                                <div class="Txt-Sz-0-90">
                                    <div><?php $user_ad_nbr = HTML_proof(getAdNbr_FromIdUser($_USER['USERS_id'])); echo $user_ad_nbr;?> annonce<?php echo_S_IfPlurial($user_ad_nbr);?> en ligne</div>
                                    <div>Membre depuis <?php echo HTML_proof(DateSinceArray_to_DateSinceStr(DateTimeStr_to_DateSinceArray($_USER['USERS_signupDatetime']), "", 2));?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>