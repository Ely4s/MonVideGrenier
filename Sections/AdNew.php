<div id="SECTION_AdNew">
    <div class="qtn qtn-V qtn-CntrH">
        <div class="Ctn Ctn-M Ctn-Clr-F Ctn-Brdr-UpDown Txt-IneritStl-Rcrsv">
            <div class="Grp-OnChld-L">
                <div class="Grp-OnChld-S qtn qtn-V qtn-CntrH">
                    <div class="qtn qtn-V qtn-CntrV Grp-OnChld-XXS">
                        <pre class="Txt-Sz-1-80 Txt-Algn-C">Créer une annonce <i class="fas fa-edit"></i></pre>
                    </div>
                </div>
                <pre class="Grp-OnChld-XXS qtn qtn-V qtn-CntrH Txt-Sz-0-80">
                            <span>Une annonce est valide <?php echo AD_VALIDITY_TIME_STR;?></span>
                            <span>après quoi, elle est automatiquement supprimé</span>
                </pre>
                <div class="qtn qtn-V qtn-CntrH">
                    <form class="Grp-OnChld-L" action="creer-une-annonce.php" method="post" enctype="multipart/form-data">
                        <div class="Grp-OnChld-XS">
                            <div class="qtn qtn-V Grp-OnChld-XXS">
                                <label id="AdNew_Title_Label" for="AdNew_Title_Input">Titre :</label>
                                <input id="AdNew_Title_Input" class="InputTxt InputTxt-XL NoMarginSide" type="text" name="title" maxlength="<?php echo HTML_proof(TITLE_CHAR_MAX)?>" autocomplete="off" value="<?php echo HTML_proof($_AD['title']);?>">
                            </div>
                            <div class="qtn qtn-V Grp-OnChld-XXS">
                                <label id="AdNew_Description_Label" for="Login_Description_Input">Description :</label>
                                <textarea id="AdNew_Description_Input" class="InputTxt InputTxt-XXL NoMarginSide" name="description" rows="15" maxlength="<?php echo HTML_proof(DESCRIPTION_CHAR_MAX)?>" autocomplete="off"><?php echo HTML_proof($_AD['description']); ?></textarea>
                            </div>
                            <div class="qtn qtn-V qtn-CntrV Grp-OnChld-XXS">
                                <label id="AdNew_Price_Label" for="AdNew_Price_Input">Prix :</label>
                                <input id="AdNew_Price_Input" class="InputTxt InputTxt-L NoMarginSide" type="text" name="price" maxlength="<?php echo HTML_proof(PRICE_CHAR_MAX)?>" autocomplete="off" value="<?php echo HTML_proof($_AD['price']);?>">
                            </div>
                            <div class="qtn qtn-V qtn-CntrV Grp-OnChld-XXS">
                                <label id="AdNew_City_Label" for="AdNew_City_Input">Ville :</label>
                                <div class="SlctSrch SlctSrch-XL">
                                    <input id="AdNew_City_Input" class="SlctSrch-Inpt NoMarginSide" type="search" name="city" maxlength="<?php echo HTML_proof(CITY_CHAR_MAX)?>" autocomplete="off" value="<?php if(!empty($_AD['city'])) echo HTML_proof($_AD['city']); else echo HTML_proof($_SESSION['city']); ?>">
                                    <div class="SlctSrch-Rslt NoMarginSide" data-index="">
                                        <?php foreach(getAllCityAndPostalCode() as $city):?>
                                            <div class="SlctSrch-Rslt-Elmnt"><?php echo HTML_proof($city[0])?> (<?php echo HTML_proof($city[1])?>)</div>
                                        <?php endforeach;?>
                                    </div>
                                </div>
                            </div>
                            <div class="qtn qtn-V qtn-CntrV Grp-OnChld-XXS">
                                <label id="AdNew_Category_Label" for="AdNew_Category_Select">Catégorie :</label>
                                <div class="Select">
                                    <select id="AdNew_Category_Select" name="category" class="InputTxt InputTxt-XL NoMarginSide">
                                        <?php for($i = 0; $i < count(AD_CATEGORIES); $i++): ?>
                                            <optgroup label="<?php echo HTML_proof(AD_CATEGORIES[$i][0]); ?>">
                                                <?php for($j = 1; $j < count(AD_CATEGORIES[$i]); $j++): ?>
                                                    <option <?php if(($i === 0 && empty($_AD['category'])) || (!empty($_AD['category']) && $_AD['category'] === AD_CATEGORIES[$i][$j])) echo "selected='selected'";?> ><?php echo AD_CATEGORIES[$i][$j]; ?></option>
                                                <?php endfor; ?>
                                            </optgroup>
                                        <?php endfor; ?>
                                    </select>
                                    <div class="SelectArrow"></div>
                                </div>
                            </div>
                        </div>
                        <div class="Grp-OnChld-XS">
                            <div class="qtn qtn-V Grp-OnChld-XXS">
                                <div class="InptFl-Lst">
                                    <?php for($i = 1; $i <= $_AD['pictureNbr'] || $i === 1; $i++):?>
                                        <div class="InptFl InputTxt-XL">
                                            <div class="InptFl-InptWrppr">
                                                Sélectionner une photo
                                                <label for="InptFl-Id-<?php echo HTML_proof($i)?>" class="InptFl-InptCvr">
                                                    <input id="InptFl-Id-<?php echo HTML_proof($i)?>" class="InptFl-Inpt" type="file" name="picture-<?php echo HTML_proof($i)?>" accept="image/png, image/jpeg" >
                                                </label>
                                            </div>
                                            <div class="InptFl-Otpt"></div>
                                            <div class="InptFl-Btn <?php if($i === 1 && $_AD['pictureNbr'] >= PICTURE_NBR_MAX) echo "hidden";?>"><i class="fas fa-<?php if($i === 1) echo "plus"; else echo "minus";?>-square"></i></div>
                                        </div>
                                    <?php endfor;?>
                                </div>
                                <input class="InptFl-NbrOfPctr hidden" name="pictureNbr" type="hidden" value="<?php if(!empty($_AD['pictureNbr'])) echo HTML_proof($_AD['pictureNbr']); else echo 1?>">
                            </div>
                        </div>
                        <input type="hidden" name="token" value="<?php echo md5(uniqid('',true)); ?>"/>
                        <div class="Grp-OnChld-XS qtn qtn-V qtn-CntrH qtn-SzInhrt">
                            <button id="AdNew_Submit" class="Btn Btn-Clr-S Btn-100 NoMarginSide Txt-Sz-1-20" name="adNew-btn" type="submit">Créer</button>
                            <pre class="Txt-Sz-0-75"><a href="mon-compte.php" class="Txt-Clr-Spe-F Txt-Bld">Annuler et retourner sur mon compte</a></pre>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>