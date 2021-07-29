<div id="SECTION_ConversationList">
    <div class="qtn qtn-V qtn-CntrH Grp-OnChld-S">
        <?php for($i = 0; $i < count($_CONVERSATIONS); $i++):?>
            <div class="Ctn Ctn-M Ctn-NoPadding">
                <div class="qtn qtn-V Grp-OnChld-S">
                    <div class="Ctn Ctn-Clr-F Ctn-NoPadding qtn qtn-H Txt-IneritStl-Rcrsv">
                        <a href="consulter-une-annonce.php?id=<?php echo HTML_proof($_CONVERSATIONS[$i]['CONVERSATIONS_id_ad']); ?>" class="Ctn Ctn-NoPadding Ctn-CnvrstnLstLeft Ctn-Clr-S Ctn-NoShadow">
                            <img class="Pic-Elmnt" src="<?php echo HTML_proof(FOLDERPATH_ADPICTURE.$_CONVERSATIONS[$i]['CONVERSATIONS_ad']['ADSPICTURES_file_name_1']); ?>">
                        </a>
                        <a href="conversation.php?id_buyer=<?php echo HTML_proof($_CONVERSATIONS[$i]['CONVERSATIONS_id_userBuyer']); ?>&id_seller=<?php echo HTML_proof($_CONVERSATIONS[$i]['CONVERSATIONS_id_userSeller']); ?>&id_ad=<?php echo HTML_proof($_CONVERSATIONS[$i]['CONVERSATIONS_id_ad']); ?>" class="Ctn Ctn-CnvrstnLstRight Ctn-Clr-F Ctn-NoShadow Ctn-SmallPadding qtn qtn-V qtn-SpcBtw">
                            <div class="Txt-Sz-1-20 Txt-Bld">
                                <?php if($_CONVERSATIONS[$i]['CONVERSATIONS_id_userSeller'] === $_SESSION['id']) echo HTML_proof($_CONVERSATIONS[$i]['CONVERSATIONS_userBuyer']['USERS_username']); else echo HTML_proof($_CONVERSATIONS[$i]['CONVERSATIONS_userSeller']['USERS_username']) ?>
                            </div>
                            <div class="qtn qtn-H qtn-CntrBsln">
                                <div class="Txt-Sz-0-80">À propos de <?php if($_CONVERSATIONS[$i]['CONVERSATIONS_id_userSeller'] === $_SESSION['id']) echo "votre "; else echo "l'" ?>annonce&nbsp</div><div class="Txt-Sz-0-90 Txt-Bld">"<?php echo HTML_proof($_CONVERSATIONS[$i]['CONVERSATIONS_ad']['ADS_title']); ?>"</div>
                            </div>
                        </a>
                        <div class="ConversationList-Btn">
                            <button id="AdUpdate_Delete" class="Btn Btn Btn-Pddng-S NoMarginSide Txt-Sz-0-80 Btn-Cnfrm-Off" name="adModify-btn" data-lnk="<?php echo HTML_proof(getURL_withSearchOption($page));?>&id_conversation=<?php echo HTML_proof($_CONVERSATIONS[$i]['CONVERSATIONS_id']); ?>&delete" data-cnfrm-txt="Confirmer <i class='fas fa-trash-alt'></i>">Supprimer <i class='fas fa-trash-alt'></i></button>
                        </div>
                    </div>
                </div>
            </div>
        <?php endfor;?>
    </div>
</div>