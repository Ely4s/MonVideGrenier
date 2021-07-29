<div id="SECTION_Ads">
    <div class="qtn qtn-H qtn-CntrH">
        <div class="qtn qtn-V Grp-OnChld-S">
            <?php for($i = $ads_index_start; $i <= $ads_index_start + $ads_index_end && $i < $ads_nbr; $i++): ?>
                <div class="Ctn Ctn-M Ctn-Clr-F Ctn-NoPadding Txt-IneritStl-Rcrsv">
                    <div class="qtn qtn-H">
                        <div class="Ctn Ctn-AdLeftTop Ctn-Clr-S Ctn-NoPadding Ctn-NoShadow">
                            <div class="Crsl">
                                <div class="Crsl-ImgGroup" data-image_actual="1">
                                    <?php for($j = 1; $j <= $ads[$i]['ADSPICTURES_nbr']; $j++):?>
                                        <img src="<?php echo FOLDERPATH_ADPICTURE.$ads[$i]['ADSPICTURES_file_name_'.$j];?>" class="show" data-num="<?php echo HTML_proof($j);?>">
                                    <?php endfor;?>
                                </div>
                                <?php if($ads[$i]['ADSPICTURES_nbr'] > 1): ?>
                                    <div class="Crsl-BtnGroup">
                                        <div class="Crsl-BtnLeft Crsl-Btn-S" onclick="Carousel_Onclick(this)"><i class="fas fa-angle-left"></i></div>
                                        <div class="Crsl-BtnRight Crsl-Btn-S" onclick="Carousel_Onclick(this)"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                <?php endif;?>
                                <pre class="Crsl-ImgNbr"><?php if($ads[$i]['ADSPICTURES_file_name_1'] != FILENAME_AD_DEFAULT) echo $ads[$i]['ADSPICTURES_nbr']; else echo "0"?> <i class="far fa-image"></i></pre>
                            </div>
                        </div>
                        <div class="Ctn Ctn-AdRightTop Ctn-Clr-F Ctn-NoPadding Ctn-NoShadow Txt-IneritStl-Rcrsv">
                            <a href="consulter-une-annonce.php?id=<?php echo HTML_proof($ads[$i]['ADS_id']);?>" class="qtn qtn-V qtn-SzInhrt qtn-SpcBtw Ctn Ctn-NoShadow">
                                <div class="qtn qtn-V">
                                    <div class="Txt-Bld" style="word-break: break-word;"><?php echo HTML_proof($ads[$i]['ADS_title']);?></div>
                                    <div class="Txt-Clr-Spe-F Txt-Bld"><?php echo HTML_proof($ads[$i]['ADS_price']);?> €</div>
                                </div>
                                <div class="qtn qtn-V Txt-Sz-0-90 Grp-OnChld-XXS">
                                    <div><?php echo HTML_proof($ads[$i]['ADS_category']);?></div>
                                    <div><?php echo HTML_proof($ads[$i]['ADS_city']);?></div>
                                    <div><?php echo DateSinceArray_to_DateSinceStr(DateTimeStr_to_DateSinceArray($ads[$i]['ADS_creationDatetime']), "Il y a ", 1);?></div>
                                </div>
                            </a>
                        </div>
                        <?php if($ads[$i]['ADS_id_user'] == $_SESSION['id']):?>
                            <a href="modifier-une-annonce.php?id=<?php echo HTML_proof($ads[$i]['ADS_id']);?>" class="A-d-btn">
                                <i class="fas fa-wrench"></i>
                            </a>
                        <?php endif;?>
                    </div>
                </div>
            <?php endfor;?>
        </div>
    </div>
</div>