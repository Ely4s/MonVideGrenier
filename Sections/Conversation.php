<div id="SECTION_Conversation">
    <div class="qtn qtn-V qtn-CntrH">
        <div class="Ctn Ctn-M Ctn-NoPadding">
            <div class="qtn qtn-V Grp-OnChld-S">
                <div class="Ctn Ctn-Clr-F Ctn-NoPadding qtn qtn-H">
                    <a href="consulter-une-annonce.php?id=<?php echo HTML_proof($_CONVERSATION['CONVERSATIONS_ad']['ADS_id']); ?>" class="Ctn Ctn-NoPadding Ctn-CnvrstnLstLeft Ctn-Clr-S Ctn-NoShadow">
                        <img class="Pic-Elmnt" src="<?php echo HTML_proof(FOLDERPATH_ADPICTURE.$_CONVERSATION['CONVERSATIONS_ad']['ADSPICTURES_file_name_1']); ?>">
                    </a>
                    <a href="conversation.php?id_buyer=<?php echo HTML_proof($_CONVERSATION['CONVERSATIONS_id_userBuyer']); ?>&id_seller=<?php echo HTML_proof($_CONVERSATION['CONVERSATIONS_id_userSeller']); ?>&id_ad=<?php echo HTML_proof($_CONVERSATION['CONVERSATIONS_id_ad']); ?>" class="Ctn Ctn-CnvrstnLstRight Ctn-Clr-F Ctn-NoShadow Ctn-SmallPadding qtn qtn-V qtn-SpcBtw">
                        <div class="Txt-Sz-1-20 Txt-Bld">
                            <?php if($_CONVERSATION['CONVERSATIONS_id_userSeller'] === $_SESSION['id']) echo HTML_proof($_CONVERSATION['CONVERSATIONS_userBuyer']['USERS_username']); else echo HTML_proof($_CONVERSATION['CONVERSATIONS_userSeller']['USERS_username']) ?>
                        </div>
                        <div class="qtn qtn-H" style="align-items: baseline">
                                <div class="Txt-Sz-0-80">À propos de <?php if($_CONVERSATION['CONVERSATIONS_id_userSeller'] === $_SESSION['id']) echo "votre "; else echo "l'" ?>annonce&nbsp</div><div class="Txt-Sz-0-90 Txt-Bld">"<?php echo HTML_proof($_CONVERSATION['CONVERSATIONS_ad']['ADS_title']); ?>"</div>
                        </div>
                    </a>
                </div>
                <div class="qtn qtn-V qtn-CntrH">
                    <div class="Ctn Ctn-M Grp-OnChld-M Ctn-NoPadding">
                        <div class="qtn qtn-V qtn-CntrH">
                            <div class="Cnvrstn">
                                <div class="Cnvrstn-Otpt" data-mssg-nbr="0">
                                    <?php for($i = 0; $i < $_CONVERSATION['CONVERSATIONS_messageNbr']; $i++):?>
                                    <div class="Cnvrstn-Mssg Mssg-<?php if($_CONVERSATION['CONVERSATIONS_message_'.($i+1)]['MESSAGES_id_user'] === $_SESSION['id']) echo "R"; else echo "L"?>">
                                        <div class="Cnvrstn-Dt"><?php echo HTML_proof($_CONVERSATION['CONVERSATIONS_message_'.($i+1)]['MESSAGES_postDatetime']);?></div>
                                        <div class="Cnvrstn-MssgTxt"><?php echo nl2br(HTML_proof($_CONVERSATION['CONVERSATIONS_message_'.($i+1)]['MESSAGES_content']));?></div>
                                    </div>
                                    <?php endfor;?>
                                </div>
                                <form action="conversation.php?id_buyer=<?php echo $_GET['id_buyer'];?>&id_seller=<?php echo $_GET['id_seller'];?>&id_ad=<?php echo $_GET['id_ad'];?>" method="post" class="Cnvrstn-Inpt">
                                    <input type="hidden" name="token" value="<?php echo md5(uniqid('',true)); ?>"/>
                                    <textarea id="messageNew-inpt" oninput="auto_grow(this,32)" class="InputTxt InputTxt-75 NoMarginSide" name="message" maxlength="<?php echo HTML_proof(MESSAGES_CHAR_MAX)?>" autocomplete="off"><?php echo HTML_proof($_MESSAGE); ?></textarea>
                                    <button id="messageNew-btn" class="Btn Btn-20 Btn-Clr-F NoMarginSide Txt-Sz-1-20" name="messageNew-btn" type="submit">Envoyer</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>