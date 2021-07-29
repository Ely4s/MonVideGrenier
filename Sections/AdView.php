<div id="SECTION_AdView">
    <div class="qtn qtn-H qtn-CntrH">
        <div class="qtn qtn-V qtn-CntrV Grp-OnChld-S">
            <div class="Ctn Ctn-L Ctn-NoPadding Ctn-NoShadow">
                <div class="qtn qtn-H qtn-SpcBtw qtn-SzInhrt">
                    <div class="Ctn Ctn-AdVisuLeft Ctn-Clr-S Ctn-NoPadding Txt-IneritStl-Rcrsv">
                        <div class="Crsl">
                            <div class="Crsl-ImgGroup" data-image_actual="1">
                                <?php for($i = 1; $i <= $_AD['ADSPICTURES_nbr']; $i++):?>
                                    <img src="<?php echo FOLDERPATH_ADPICTURE.$_AD['ADSPICTURES_file_name_'.$i];?>" class="show" data-num="<?php echo HTML_proof($i);?>">
                                <?php endfor;?>
                            </div>
                            <?php if($_AD['ADSPICTURES_nbr'] > 1): ?>
                                <div class="Crsl-BtnGroup">
                                    <div class="Crsl-BtnLeft Crsl-Btn-M" onclick="Carousel_Onclick(this)"><i class="fas fa-angle-left"></i></div>
                                    <div class="Crsl-BtnRight Crsl-Btn-M" onclick="Carousel_Onclick(this)"><i class="fas fa-angle-right"></i></div>
                                </div>
                            <?php endif;?>
                            <pre class="Crsl-ImgNbr"><?php echo HTML_proof($_AD['ADSPICTURES_nbr']);?> <i class="far fa-image"></i></pre>

                        </div>
                    </div>
                    <div class="Ctn Ctn-Clr-F Ctn-AdVisuRight Ctn-NoPadding Ctn-Brdr-Up Ctn-Brdr-Down Txt-IneritStl-Rcrsv">
                        <div class="qtn qtn-V qtn-SpcBtw qtn-SzInhrt">
                            <div class="Ctn Ctn-AdVisuRightTop Ctn-NoShadow Ctn-NoPadding">
                                <div class="qtn qtn-H">
                                    <div class="Ctn Ctn-NoShadow Ctn-NoPddngR">
                                        <div class="PrflPic PrflPic-XS"><img class="PrflPic-Elmnt" src="<?php echo HTML_proof(FOLDERPATH_PROFILPICTURE.$_USER['USERS_profilPicturePath']);?>"></div>
                                    </div>
                                    <div class="Ctn Ctn-NoShadow Ctn-SmallPadding">
                                        <div>
                                            <div class="Txt-Sz-1-10 Txt-Bld"><?php echo HTML_proof($_USER['USERS_username']);?></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="Ctn Ctn-NoShadow">
                                <div class="qtn qtn-V qtn-CntrV qtn-SzInhrt">
                                    <?php if(isset($_SESSION['id'])):?>
                                        <button class="Btn Btn-Clr-S Btn-100 Btn-Pddng-M Txt-Sz-0-80" onclick="location.href='profil.php?id=<?php echo HTML_proof($_USER["USERS_id"]);?>';"><pre>Voir le profil du vendeur  <i class="far fa-address-card"></i></pre></button>
                                        <?php if($_USER['USERS_emailPrivacy'] === 0):?>
                                        <button class="Btn Btn-Clr-S Btn-100 Btn-Pddng-M Txt-Sz-0-80 Btn-Rvl-Off" data-hddn-txt="<?php echo HTML_proof($_USER['USERS_email']);?>"><pre>Voir le mail  <i class="far fa-envelope"></i></pre></button>
                                        <?php endif;?>
                                        <?php if($_USER['USERS_phoneNumberPrivacy'] === 0):?>
                                        <button class="Btn Btn-Clr-S Btn-100 Btn-Pddng-M Txt-Sz-0-80 Btn-Rvl-Off" data-hddn-txt="<?php echo HTML_proof($_USER['USERS_phoneNumber']);?>"><pre>Voir le numéro de téléphone  <i class="far fa-phone"></i></pre></button>
                                        <?php endif;?><form action="consulter-une-annonce.php?id=<?php echo HTML_proof($_AD['ADS_id']);?>" method="post"><button class="Btn Btn-Clr-S Btn-100 Btn-Pddng-M Txt-Sz-0-80" name="conversationNew-btn"><pre>Envoyer un message  <i class="far fa-comment"></i></pre></button></form>
                                    <?php else:?>
                                        <div class="Ctn Txt-Algn-C Txt-Sz-0-80" onclick="location.href='connection.php"><pre>Connectez-vous pour</pre><pre>communiquer avec le vendeur</pre></div>
                                        <button class="Btn Btn-Clr-S Btn-100 Btn-NoBrdrTp Btn-Pddng-M NoMargin Txt-Sz-1-20" onclick="location.href='connection.php'"><pre>Connection <i class="fas fa-sign-in-alt"></i></pre></button>
                                    <?php endif;?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="qtn qtn-V Ctn Ctn-L Ctn-Clr-F Ctn-Brdr-Up Ctn-NoShadow Grp-OnChld-M Grp-OnSlf-S Txt-IneritStl-Rcrsv">
                <div class="Txt-Sz-1-20 Txt-Bld"><?php echo HTML_proof($_AD['ADS_title']);?></div>
                <div class="Grp-OnChld-XXS">
                    <div class="Txt-Sz-1-20 Txt-Bld Txt-Clr-Spe-F"><?php echo HTML_proof($_AD['ADS_price']);?> €</div>
                    <div class="Txt-Sz-0-90"><?php echo HTML_proof(DateSinceArray_to_DateSinceStr(DateTimeStr_to_DateSinceArray($_AD['ADS_creationDatetime']), "Il y a ", 2).", à ".$_AD['ADS_city']);?></div>
                </div>
            </div>
            <div class="qtn qtn-V Ctn Ctn-L Ctn-Clr-F Ctn-Brdr-Down Ctn-NoShadow Grp-OnChld-L Txt-IneritStl-Rcrsv">
                <div class="Txt-Sz-1-20 Txt-Bld">Description</div>
                <div class="Grp-OnChld-XXS">
                    <div class="Txt-Sz-0-90 Txt-WrdBrk">
                        <?php echo nl2br($_AD['ADS_description']);?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>