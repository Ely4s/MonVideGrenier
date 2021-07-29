<?php require_once 'Functions/SearchFunctions.php';?>

<div id="SECTION_Pagination">
    <div class="qtn qtn-H qtn-CntrH">
        <div class="Ctn Ctn-M Ctn-Clr-F Ctn-Brdr-Down Ctn-SmallPadding">
            <div class="qtn qtn-H qtn-CntrH">
                <div class="Pgntn">
                    <?php if($page_nbr_before > 0):?>
                    <div class="Pgntn-Element"><a href="<?php echo HTML_proof(getURL_withSearchOption($page-1));?>"><i class="fas fa-angle-left"></i></a></div>
                    <?php endif;?>
                    <?php for($i = $page - $page_nbr_before; $i <= $page + $page_nbr_after; $i++):?>
                    <div class="Pgntn-Element <?php if($i === $page) echo "active";?>"><a href="<?php echo HTML_proof(getURL_withSearchOption($i));?>"><?php echo $i;?></a></div>
                    <?php endfor;?>
                    <?php if($page_nbr_after > 0):?>
                    <div class="Pgntn-Element"><a href="<?php echo HTML_proof(getURL_withSearchOption($page+1));?>"><i class="fas fa-angle-right"></i></a></div>
                    <?php endif;?>
                </div>
            </div>
        </div>
    </div>
</div>