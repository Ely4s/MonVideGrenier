<div id="SECTION_ErrorsAndSuccess">
    <div class="qtn qtn-V qtn-CntrH Grp-OnChld-S">
        <?php if(isset($_SESSION['success']) && !(empty($_SESSION['success']))): ?>
            <div class="Ctn Ctn-S Ctn-Clr-Success Ctn-Brdr-Up Ctn-Brdr-Down Ctn-SmallPadding Txt-IneritStl-Rcrsv">
                <ul>
                    <?php foreach ($_SESSION['success'] as $success): ?>
                        <li><?php echo HTML_proof($success); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php unset($_SESSION['success']); $_SESSION['success'] = array();?>
        <?php endif; ?>
        <?php if(isset($_SESSION['errors']) && !(empty($_SESSION['errors']))):?>
            <div class="Ctn Ctn-S Ctn-Clr-Error Ctn-Brdr-Up Ctn-Brdr-Down Ctn-SmallPadding Txt-IneritStl-Rcrsv">
                <ul>
                    <?php foreach ($_SESSION['errors'] as $error): ?>
                        <li><?php echo HTML_proof($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php if(isset($_SESSION['errors']['User_Disconnect'])){session_destroy();}?>
            <?php unset($_SESSION['errors']); $_SESSION['errors'] = array();?>
        <?php endif; ?>
    </div>
</div>