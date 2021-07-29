<div id="SECTION_SearchEngin">
    <div class="qtn qtn-H qtn-CntrH">
        <div class="Ctn Ctn-S Ctn-Clr-F Ctn-Brdr-UpDown Txt-IneritStl-Rcrsv">
            <div class="qtn qtn-H qtn-CntrH">
                <div class="qtn qtn-V">
                    <form class="Grp-OnChld-S" action="<?php $_SERVER['PHP_SELF'] ;?>" method="get">
                        <pre class="Txt-Sz-1-40 Txt-Algn-C">Rechercher <?php if(end(explode('/',$_SERVER['PHP_SELF'])) === "mon-compte.php") echo "parmis mes annonces"; elseif(end(explode('/',$_SERVER['PHP_SELF'])) === "profil.php") echo "parmis ses annonces"; else echo "une annonce"?> <i class="fas fa-search"></i></pre>
                        <div class="Grp-OnChld-XXS">
                            <div class="qtn qtn-H qtn-CntrH">
                                <input id="SearchEngin_SearchKeyWord_Input" class="InputTxt InputTxt-L" type="search" name="search" autocomplete="off" value="<?php echo HTML_proof($_GET['search']);?>">
                                <button id="SearchEngin_SearchKeyWord_Submit" class="Btn Btn-Clr-S" type="submit">Rechercher</button>
                            </div>
                            <div class="qtn qtn-H qtn-CntrH">
                                <label id="SearchEngin_SearchCategory_Label" for="SearchEngin_SearchCategory_Select">Catégorie :</label>
                                <div class="Select">
                                    <select name="category" class="InputTxt InputTxt-L">
                                        <?php for($i = 0; $i < count(AD_CATEGORIES); $i++): ?>
                                            <optgroup label="<?php echo HTML_proof(AD_CATEGORIES[$i][0]); ?>">
                                                <?php for($j = 1; $j < count(AD_CATEGORIES[$i]); $j++): ?>
                                                    <option <?php if(AD_CATEGORIES[$i][$j] === $_GET['category']) echo "selected='selected'"?> ><?php echo HTML_proof(AD_CATEGORIES[$i][$j]); ?></option>
                                                <?php endfor; ?>
                                            </optgroup>
                                        <?php endfor; ?>
                                    </select>
                                    <div class="SelectArrow"></div>
                                </div>
                            </div>
                            <div class="qtn qtn-H qtn-CntrH">
                                <label id="SearchEngin_SearchPrice_Label" for="SearchEngin_SearchKeyWord_Price">Affiner par prix :</label>
                                <input id="SearchEngin_SearchKeyWord_PriceMin" class="InputTxt InputTxt-S" type="text" name="priceMin" autocomplete="off" placeholder="Min" value="<?php echo HTML_proof($_GET['priceMin']);?>">
                                <input id="SearchEngin_SearchKeyWord_PriceMax" class="InputTxt InputTxt-S" type="text" name="priceMax" autocomplete="off" placeholder="Max" value="<?php echo HTML_proof($_GET['priceMax']);?>">
                            </div>
                            <?php if(end(explode('/',$_SERVER['PHP_SELF'])) === "index.php"):?>
                            <div class="qtn qtn-H qtn-CntrH">
                                <label id="AdNew_City_Label" for="AdNew_City_Input">Ville :</label>
                                <div class="SlctSrch SlctSrch-L">
                                    <input id="AdNew_City_Input" class="SlctSrch-Inpt" type="search" name="city" maxlength="<?php echo HTML_proof(CITY_CHAR_MAX)?>" autocomplete="off" value="<?php echo HTML_proof($_GET['city']); ?>">
                                    <div class="SlctSrch-Rslt" data-index="">
                                        <?php foreach(getAllCityAndPostalCode() as $city):?>
                                            <div class="SlctSrch-Rslt-Elmnt"><?php echo HTML_proof($city[0])?> (<?php echo HTML_proof($city[1])?>)</div>
                                        <?php endforeach;?>
                                    </div>
                                </div>
                                <input class="InputTxt InputTxt-S" type="text" name="cityKm" autocomplete="off" placeholder="jusqu'à" value="<?php echo HTML_proof($_GET['cityKm']); ?>">Km
                            </div>
                            <?php endif;?>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
