//global let

let InptFl_Otpt_IfEmptTxt = "Aucun fichier sélectionné";
let InptFl_Lst_MaxInptFl = 3;
let SlctSrch_Rslt_MaxOutput = 5;
//

document.addEventListener('DOMContentLoaded', function(event)
{
    //Crsl
    let div_ImageGroup = document.getElementsByClassName('Crsl-ImgGroup');
    for(let i = 0; i < div_ImageGroup.length; i++)
    {
        let img = div_ImageGroup[i].getElementsByTagName('img');

        for(let j = 0; j < img.length; j++)
        {
            if(j === 0) {img[j].classList.add("show");}
            else        {img[j].classList.add("hidden");}
        }
    }

    //SlctSrch
    let divs;
    divs = document.getElementsByClassName('SlctSrch-Inpt');
    for(let i = 0; i < divs.length; i++)
    {
        divs[i].addEventListener('focusin', SlctSrch_Inpt_In);
        divs[i].addEventListener('focusout', function(){SlctSrch_Inpt_Out(this)});
        divs[i].addEventListener('search', SlctSrch_Inpt_Clear);
        divs[i].addEventListener('keyup', SlctSrch_Inpt_KeyUp);
    }

    divs = document.getElementsByClassName('SlctSrch-Rslt-Elmnt');
    for(let i = 0; i < divs.length; i++) {
        divs[i].addEventListener('mouseover', SlctSrch_Rslt_Elmnt_MouseOver);
        divs[i].addEventListener('mouseout', SlctSrch_Rslt_Elmnt_MouseOut);
        divs[i].addEventListener('click', SlctSrch_Rslt_Elmnt_Click);
    }

    //ButtonRevel
    divs = document.getElementsByClassName('Btn-Rvl-Off');
    for(let i = 0; i < divs.length; i++) divs[i].addEventListener('click', function (){Btn_Rvl_Off_Click(this)});

    //CSS color
    let varis = ['--Clr-Cntnr-F', '--Clr-Cntnt-F', '--Clr-Cntnt-Spe-F', '--Clr-Cntnr-S', '--Clr-Cntnt-S', '--Clr-Cntnt-Spe-S', '--Clr-Contr-Succ', '--Clr-Contr-Erro'];
    let vars_rgb_extntn = ['-R', '-G', '-B'];

    varis.forEach(function(vari, index, array)
    {
        let root = document.documentElement;

        let hex = getComputedStyle(root).getPropertyValue(vari).trim();
        let rgb = hexToRgb(hex);

        root.style.setProperty(vari+vars_rgb_extntn[0], rgb[0]);
        root.style.setProperty(vari+vars_rgb_extntn[1], rgb[1]);
        root.style.setProperty(vari+vars_rgb_extntn[2], rgb[2]);

    });

    //Disable Img drag
    let imgs = document.getElementsByTagName('img');
    for(let i = 0; i < imgs.length; i++) imgs[i].ondragstart = function() { return false; };

    //Switcher
    divs = document.getElementsByClassName("Swtchr-Elmnt");
    for(let i = 0; i < divs.length; i++)
    {
        divs[i].addEventListener('click', function() { Swtchr_Elmnt_Click(this)})
    }

    //InptFl
    divs = document.getElementsByClassName("InptFl");
    for(let i = 0; i < divs.length; i++)
    {
        let InptFl_Inpt = divs[i].getElementsByClassName("InptFl-Inpt")[0];
        InptFl_Inpt.addEventListener('change', InptFl_Inpt_Change);

        let InptFl_Otpt = divs[i].getElementsByClassName("InptFl-Otpt")[0];
        InptFl_Otpt.innerHTML = InptFl_Otpt_IfEmptTxt;

        let InptFl_Btn = divs[i].getElementsByClassName("InptFl-Btn")[0];
        InptFl_Btn.addEventListener('click', InptFl_Btn_Click);
    }

    //textarea
    let textareas = document.getElementsByTagName('textarea');
    let count = textareas.length;
    for(let i = 0; i < count; i++)
    {
        textareas[i].addEventListener("keydown", TextArea_KeyDown);
    }

    //Chng-PrflPic
    divs = document.getElementsByClassName("Chng-PrflPic");
    for(let i = 0; i < divs.length; i++)
    {
        let input = divs[i].getElementsByTagName('input')[0];
        if(input.type.toLowerCase() === 'file')
        {
            input.addEventListener("change", function(event){Chng_PrflPic_Input_OnChange(this, event)});
        }
    }

    //Scroll to bottom Cnvrstn-Otpt
    divs = document.getElementsByClassName("Cnvrstn-Otpt");
    for(let i = 0; i < divs.length; i++)
    {
        divs[i].scrollTop = divs[i].scrollHeight;
    }

    divs = document.getElementsByClassName("Btn-Cnfrm-Off");
    for(let i = 0; i < divs.length; i++)
    {
        divs[i].addEventListener('click', function (event) {Btn_Cnfrm_Off_OnClick(event)})
    }





});

function nl2br (str, replaceMode, isXhtml) {

    let breakTag = (isXhtml) ? '<br />' : '<br>';
    let replaceStr = (replaceMode) ? '$1'+ breakTag : '$1'+ breakTag +'$2';
    return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, replaceStr);
}

function Btn_Cnfrm_Off_OnClick(event)
{
    let elmnt = event.currentTarget;
    let link = elmnt.getAttribute('data-lnk');
    let txt = elmnt.getAttribute('data-cnfrm-txt');

    elmnt.addEventListener('click', function () {location.href=link});

    let height = elmnt.clientHeight;
    let width = elmnt.clientWidth;

    elmnt.innerHTML = txt;

    if(elmnt.clientHeight < height)
    {
        elmnt.style.height = height;
    }
    if(elmnt.clientWidth < width)
    {
        elmnt.style.width = width;
    }
}

function auto_grow(element, size)
{
    element.style.height = size+"px";
    element.style.height = (element.scrollHeight)+"px";
}

function SlctSrch_Rslt_Elmnt_MouseOver(event)
{
    let SlctSrch_Rslt_Elmnt = event.currentTarget;
    SlctSrch_Rslt_Elmnt.classList.add("hover");
}
function SlctSrch_Rslt_Elmnt_MouseOut(event)
{
    let SlctSrch_Rslt_Elmnt = event.currentTarget;
    SlctSrch_Rslt_Elmnt.classList.remove("hover");
}
function SlctSrch_Rslt_Elmnt_Click(event)
{
    let SlctSrch_Rslt_Elmnt = event.currentTarget;
    let SlctSrch_Rslt = SlctSrch_Rslt_Elmnt.parentNode;
    let SlctSrch_Rslt_Elmnt_Hover = SlctSrch_Rslt.getElementsByClassName("hover")[0];
    let SlctSrch_Inpt = SlctSrch_Rslt.parentElement.getElementsByClassName("SlctSrch-Inpt")[0];
    if(SlctSrch_Rslt_Elmnt_Hover)
    {
        SlctSrch_Rslt_Elmnt_Hover.classList.remove("hover");
        SlctSrch_Inpt.value = SlctSrch_Rslt_Elmnt_Hover.innerHTML;
        SlctSrch_Inpt_Out(SlctSrch_Inpt);
    }
}

function SlctSrch_Inpt_In(event)
{
    let SlctSrch_Inpt = event.currentTarget;
    let SlctSrch_Inpt_Str = SlctSrch_Inpt.value.toString();
    if(SlctSrch_Inpt_Str !== "") SlctSrch_Inpt.classList.add("focus");

    let SlctSrch_Rslt = event.currentTarget.parentNode.getElementsByClassName("SlctSrch-Rslt")[0];
    if(SlctSrch_Inpt_Str !== "") SlctSrch_Rslt.style.display = "flex";

    SlctSrch_Inpt_KeyUp(event);

    SlctSrch_Inpt.focus();
}

function SlctSrch_Inpt_Out(elmnt)
{
    let SlctSrch_Inpt = elmnt;
    let SlctSrch_Rslt = SlctSrch_Inpt.parentNode.getElementsByClassName("SlctSrch-Rslt")[0];
    let SlctSrch_Rslt_Elmnt_Hover = SlctSrch_Rslt.getElementsByClassName("hover")[0];

    if(!SlctSrch_Rslt_Elmnt_Hover)
    {
        SlctSrch_Inpt.classList.remove("focus");
        SlctSrch_Rslt.style.display = "none";
        SlctSrch_Inpt.blur();
    }
}

function SlctSrch_Inpt_Clear(event)
{
    let SlctSrch_Inpt = event.currentTarget;
    let SlctSrch_Rslt = SlctSrch_Inpt.parentNode.getElementsByClassName("SlctSrch-Rslt")[0];

    let SlctSrch_Rslt_Elmnt_array = SlctSrch_Rslt.getElementsByClassName("SlctSrch-Rslt-Elmnt");
    if(SlctSrch_Rslt.getAttribute("data-index").length > 1)
    {
        let SlctSrch_Rslt_Index = SlctSrch_Rslt.getAttribute("data-index").split(";");
        for(let i = 0; i < SlctSrch_Rslt_Index.length; i++)
        {
            SlctSrch_Rslt_Elmnt_array[SlctSrch_Rslt_Index[i]].style.display = "none";
        }
    }
    SlctSrch_Rslt.setAttribute("data-index", "");

    let div_Nofound = SlctSrch_Rslt.getElementsByClassName('SlctSrch-Rslt-Elmnt-NoFound')[0];
    if(div_Nofound) removeElement(div_Nofound);

    SlctSrch_Inpt_Out(event.currentTarget);
}

function SlctSrch_Inpt_KeyUp(event)
{
    let SlctSrch_Inpt = event.currentTarget;
    let SlctSrch_Inpt_Str = SlctSrch_Inpt.value.toString().toLowerCase();

    if(SlctSrch_Inpt_Str !== "") SlctSrch_Inpt.classList.add("focus");
    else SlctSrch_Inpt.classList.remove("focus");

    let SlctSrch_Rslt = event.currentTarget.parentNode.getElementsByClassName("SlctSrch-Rslt")[0];
    let SlctSrch_Rslt_Elmnt_array = SlctSrch_Rslt.getElementsByClassName("SlctSrch-Rslt-Elmnt");

    if(SlctSrch_Inpt_Str !== "") SlctSrch_Rslt.style.display = "flex";
    else SlctSrch_Rslt.style.display = "none";

    let SlctSrch_Rslt_Index = [];
    if(SlctSrch_Rslt.getAttribute("data-index").length > 1)
    {
        SlctSrch_Rslt_Index = SlctSrch_Rslt.getAttribute("data-index").split(";");
        for(let i = 0; i < SlctSrch_Rslt_Index.length; i++)
        {
            SlctSrch_Rslt_Elmnt_array[SlctSrch_Rslt_Index[i]].style.display = "none";
        }
        SlctSrch_Rslt_Index = [];
    }

    if(SlctSrch_Inpt_Str !== "")
    {
        for(let i = 0; i < SlctSrch_Rslt_Elmnt_array.length; i++)
        {
            let SlctSrch_Rslt_Elmnt_StrCuted = SlctSrch_Rslt_Elmnt_array[i].innerHTML.toString().substring(0, SlctSrch_Inpt_Str.length).toLowerCase();

            if(SlctSrch_Rslt_Elmnt_StrCuted === SlctSrch_Inpt_Str)
            {
                for (let j = i; j < i + SlctSrch_Rslt_MaxOutput && j < SlctSrch_Rslt_Elmnt_array.length; j++)
                {
                    SlctSrch_Rslt_Elmnt_array[j].style.display = "block";
                    SlctSrch_Rslt_Index.push(j.toString());
                }
                break;
            }
        }
        if(SlctSrch_Rslt_Index.length === 0)
        {
            let div_Nofound = SlctSrch_Rslt.getElementsByClassName('SlctSrch-Rslt-Elmnt-NoFound')[0];
            if(!div_Nofound)
            {
                div_Nofound = document.createElement("div");
                div_Nofound.classList.add('SlctSrch-Rslt-Elmnt');
                div_Nofound.classList.add('SlctSrch-Rslt-Elmnt-NoFound');
                div_Nofound.style.display = "block";
                div_Nofound.innerHTML = "Aucun résultat";

                SlctSrch_Rslt.appendChild(div_Nofound);
            }
        }
        else
        {
            let div_Nofound = SlctSrch_Rslt.getElementsByClassName('SlctSrch-Rslt-Elmnt-NoFound')[0];
            if(div_Nofound) {removeElement(div_Nofound);}
        }

        SlctSrch_Rslt.setAttribute("data-index", SlctSrch_Rslt_Index.join(';'));
    }
    else
    {
        SlctSrch_Rslt.setAttribute("data-index", "");
    }
}


function TextArea_KeyDown(event)
{
    if(event.keyCode ===9 || event.which === 9)
    {
        event.preventDefault();
        let cursor = this.selectionStart;
        this.value = this.value.substring(0,this.selectionStart) + "\t" + this.value.substring(this.selectionEnd);
        this.selectionEnd = cursor+1;
    }
}

function InptFl_Btn_Click(event)
{
    let parser = new DOMParser();
    let root = event.currentTarget.parentNode.parentNode;
    let nbrof_InptFl = root.getElementsByClassName("InptFl").length;
    let InptFl_NbrOfPctr = root.parentNode.getElementsByClassName("InptFl-NbrOfPctr")[0];
    let btn_index = Array.prototype.indexOf.call(event.currentTarget.parentNode.parentNode.children, event.currentTarget.parentNode);

    if(btn_index === 0 && nbrof_InptFl < InptFl_Lst_MaxInptFl)
    {
        let nw_elmnt =

        '<div class="InptFl">'+
            '<div class="InptFl-InptWrppr">'+
                'Sélectionner une photo'+
                '<label for="InptFl-Id-'+(nbrof_InptFl+1)+'" class="InptFl-InptCvr"><input id="InptFl-Id-'+(nbrof_InptFl+1)+'" class="InptFl-Inpt" type="file" name="picture-'+(nbrof_InptFl+1)+'" accept="image/png, image/jpeg" ></label>'+
            '</div>'+
            '<div class="InptFl-Otpt"></div>'+
            '<div class="InptFl-Btn"><i class="fas fa-minus-square"></i></div>'+
        '</div>';

        let html = parser.parseFromString(nw_elmnt, 'text/html');

        let InptFl_Inpt = html.getElementsByClassName("InptFl-Inpt")[0];
        InptFl_Inpt.addEventListener('change', InptFl_Inpt_Change);

        let InptFl_Otpt = html.getElementsByClassName("InptFl-Otpt")[0];
        InptFl_Otpt.innerHTML = InptFl_Otpt_IfEmptTxt;

        let InptFl_Btn = html.getElementsByClassName("InptFl-Btn")[0];
        InptFl_Btn.addEventListener('click', InptFl_Btn_Click);

        root.append(html.body.lastChild);

        if(nbrof_InptFl + 1 >= InptFl_Lst_MaxInptFl)
        {
            let InptFl = root.getElementsByClassName("InptFl")[0];
            let InptFl_Btn = InptFl.getElementsByClassName("InptFl-Btn")[0];
            InptFl_Btn.classList.add("hidden");
        }
    }
    else if(btn_index !== 0)
    {
        let InptFl = root.getElementsByClassName("InptFl")[btn_index];

        let InptFl_Inpt = InptFl.getElementsByClassName("InptFl-Inpt")[0];
        InptFl_Inpt.removeEventListener('change', InptFl_Inpt_Change);

        let InptFl_Btn = InptFl.getElementsByClassName("InptFl-Btn")[0];
        InptFl_Btn.removeEventListener('click', InptFl_Btn_Click);

        removeElement(InptFl);

        InptFl = root.getElementsByClassName("InptFl")[0];
        InptFl_Btn = InptFl.getElementsByClassName("InptFl-Btn")[0];
        InptFl_Btn.classList.remove("hidden");
    }

    InptFl_NbrOfPctr.value = root.getElementsByClassName("InptFl").length;
}

function removeElement(node)
{
    node.parentNode.removeChild(node);
}

function InptFl_Inpt_Change(event)
{
    let root = event.currentTarget.parentNode.parentNode.parentNode;
    let fl_name = event.target.files[0].name;
    let InptFl_Otpt = root.getElementsByClassName("InptFl-Otpt");

    InptFl_Otpt[0].innerHTML = fl_name;
}

function Swtchr_Elmnt_Click(elmnt)
{
    let root = elmnt.parentNode.parentNode;
    let root_id = elmnt.parentNode.getAttribute('data-id');
    let elmnt_id = elmnt.getAttribute('data-action');

    let divs = root.getElementsByClassName("Swtchr-Elmnt");
    for(let i = 0; i < divs.length; i++) {divs[i].classList.remove("active");}
    elmnt.classList.add("active");

    divs = document.getElementsByClassName("Swtchr-Trgt");
    for(let i = 0; i < divs.length; i++)
    {
        let show = 0;
        let sameId = 0;
        let vld_src_ids = divs[i].getAttribute("data-vld-src-id").split(';');
        for(let j = 0; j < vld_src_ids.length; j++)
        {
            let vld_src_ids_cut = vld_src_ids[j].split('-');
            if((vld_src_ids_cut[0] === root_id) && (vld_src_ids_cut[1] === elmnt_id)) {show = 1;}
            if((vld_src_ids_cut[0] === root_id)) {sameId = 1;}
        }
        if(show === 1) {divs[i].classList.remove("hidden");}
        if(show === 0 && sameId === 1) {divs[i].classList.add("hidden");}
    }
}

function hexToRgb(hex)
{
    let c;
    if(/^#([A-Fa-f0-9]{3}){1,2}$/.test(hex)){
        c= hex.substring(1).split('');
        if(c.length === 3){
            c= [c[0], c[0], c[1], c[1], c[2], c[2]];
        }
        c= '0x'+c.join('');
        return [(c>>16)&255, (c>>8)&255, c&255];
    }
    throw new Error('Bad Hex');
}

function getOrientation(file)
{
    let reader = new FileReader();
    reader.onload = function(e) {

        let view = new DataView(e.target.result);
        if (view.getUint16(0, false) !== 0xFFD8)
        {
            return -2;
        }
        let length = view.byteLength, offset = 2;
        while (offset < length)
        {
            if (view.getUint16(offset+2, false) <= 8) return -1;
            let marker = view.getUint16(offset, false);
            offset += 2;
            if (marker === 0xFFE1)
            {
                if (view.getUint32(offset += 2, false) !== 0x45786966)
                {
                    return -1;
                }

                let little = view.getUint16(offset += 6, false) === 0x4949;
                offset += view.getUint32(offset + 4, little);
                let tags = view.getUint16(offset, little);
                offset += 2;
                for (let i = 0; i < tags; i++)
                {
                    if (view.getUint16(offset + (i * 12), little) === 0x0112)
                    {
                        return view.getUint16(offset + (i * 12) + 8, little);
                    }
                }
            }
            else if ((marker & 0xFF00) !== 0xFF00)
            {
                break;
            }
            else
            {
                offset += view.getUint16(offset, false);
            }
        }
        return -1;
    };
    reader.readAsArrayBuffer(file);
}

function Chng_PrflPic_Input_OnChange(elmnt, event)
{
    let output = elmnt.parentNode.parentElement.getElementsByClassName('PrflPic-Elmnt')[0];
    output.src = URL.createObjectURL(event.target.files[0]);

    let side;
    side = getOrientation(event.target.files[0], function (){});

    let angle;

    switch (side)
    {
        case 6 :
            angle = 90;
            break;
        case 8:
            angle = -90;
            break;
        case 3:
            angle = 180;
            break;
    }

    output.style.transform = 'rotate(' + angle +'deg)';
}

function Btn_Rvl_Off_Click(elmnt)
{
    elmnt.classList.remove('Btn-Rvl-Off');
    elmnt.classList.add('Btn-Rvl-On');
    elmnt.innerHTML = elmnt.getAttribute('data-hddn-txt');
    //elmnt.removeEventListener('click', Btn_Rvl_Off_Click);
}

function Carousel_Onclick(element)
{
    let plus = 0;
    if(element.classList.contains('Crsl-BtnLeft')) {plus = -1;}
    else if(element.classList.contains('Crsl-BtnRight')) {plus = 1;}

    let div_Carousel = element.parentNode.parentNode;
    let div_ImageGroup = div_Carousel.getElementsByClassName('Crsl-ImgGroup')[0];
    let data_ImageGroup = parseInt(div_ImageGroup.getAttribute('data-image_actual'));
    let divs_Img = div_ImageGroup.getElementsByTagName('img');

    if(data_ImageGroup+plus < 1)
    {
        data_ImageGroup = divs_Img.length;
        plus = 0;
    }
    else if(data_ImageGroup+plus > divs_Img.length)
    {
        data_ImageGroup = 1;
        plus = 0;
    }

    div_ImageGroup.setAttribute('data-image_actual', ""+(data_ImageGroup+plus));

    let dataNew_ImageGroup = parseInt(div_ImageGroup.getAttribute('data-image_actual'));

    for (const img of div_ImageGroup.getElementsByTagName('img'))
    {
        if(parseInt(img.getAttribute("data-num")) === dataNew_ImageGroup)
        {
            img.classList.remove("hidden");
            img.classList.add("show");
        }
        else
        {
            img.classList.remove("show");
            img.classList.add("hidden");
        }
    }
}


/*
Functions SlctSrch_Srch_Elmnt_Int(element)
{
    element.classList.add("hover");
}

Functions SlctSrch_Srch_Elmnt_Out(element)
{
    element.classList.remove("hover");
}

Functions SlctSrch_Srch_Elmnt_Click(element)
{
    element.classList.remove("hover");
    element.parentNode.parentElement.getElementsByClassName('SlctSrch-Inpt')[0].value = element.innerHTML;
    element.parentNode.parentElement.getElementsByClassName('SlctSrch-Rslt')[0].style.display = 'none';
    document.activeElement.blur();
}

Functions SlctSrch_Inpt_In(element)
{
    SlctSrch_Inpt_KeyUp(element);
}

Functions SlctSrch_Inpt_Out(element)
{
    let divs = element.parentNode.parentElement.getElementsByClassName('SlctSrch-Rslt')[0].getElementsByClassName('SlctSrch-Rslt-Elmnt');

    for (let i = 0; i < divs.length; i++)
    {
        if (divs[i].classList.contains("hover"))
        {
            element.parentNode.parentElement.getElementsByClassName('SlctSrch-Inpt')[0].focus();
            return;
        }
    }
    element.parentNode.parentElement.getElementsByClassName('SlctSrch-Rslt')[0].style.display = 'none';

    let div_Nofound = element.parentNode.parentElement.getElementsByClassName('SlctSrch-Rslt')[0].getElementsByClassName('SlctSrch-Rslt-Elmnt-NoFound')[0];
    if (div_Nofound) element.parentNode.parentElement.getElementsByClassName('SlctSrch-Rslt')[0].removeChild(div_Nofound);

    element.classList.remove("focus");
}

Functions SlctSrch_Inpt_KeyUp(element)
{
    if(element.value.length === 0)
    {
        element.parentNode.parentElement.getElementsByClassName('SlctSrch-Rslt')[0].style.display = 'none';
        element.classList.remove("focus");
    }
    else
    {
        element.classList.add("focus");

        let div_Nofound = element.parentNode.parentElement.getElementsByClassName('SlctSrch-Rslt')[0].getElementsByClassName('SlctSrch-Rslt-Elmnt-NoFound')[0];
        if(div_Nofound)element.parentNode.parentElement.getElementsByClassName('SlctSrch-Rslt')[0].removeChild(div_Nofound);

        let divs = element.parentNode.parentElement.getElementsByClassName('SlctSrch-Rslt')[0].getElementsByClassName('SlctSrch-Rslt-Elmnt');

        let j = 0;
        let k = 0;
        for(let i = 0; i < divs.length; i++)
        {
            if(element.value.toString().toLowerCase() === divs[i].innerHTML.toString().toLowerCase().substring(0, element.value.length) && j < 4)
            {
                divs[i].style.display = "block";
                j++;
            }
            else
            {
                divs[i].style.display = "none";
                k++;
            }
        }

        element.parentNode.parentElement.getElementsByClassName('SlctSrch-Rslt')[0].style.display = 'flex';

        if(divs.length === k)
        {
            let div_Nofound = element.parentNode.parentElement.getElementsByClassName('SlctSrch-Rslt')[0].getElementsByClassName('SlctSrch-Rslt-Elmnt-NoFound')[0];
            if(!div_Nofound)
            {
                div_Nofound = document.createElement("div");
                div_Nofound.classList.add('SlctSrch-Rslt-Elmnt');
                div_Nofound.classList.add('SlctSrch-Rslt-Elmnt-NoFound');
                div_Nofound.innerHTML = "Aucun résultat";

                element.parentNode.parentElement.getElementsByClassName('SlctSrch-Rslt')[0].appendChild(div_Nofound);
            }
        }
        else
        {
            let div_Nofound = element.parentNode.parentElement.getElementsByClassName('SlctSrch-Rslt')[0].getElementsByClassName('SlctSrch-Rslt-Elmnt-NoFound')[0];
            if(div_Nofound)element.parentNode.parentElement.getElementsByClassName('SlctSrch-Rslt')[0].removeChild(div_Nofound);
        }
    }
}

Functions SlctSrch_Inpt_Clear(element)
{
    SlctSrch_Inpt_KeyUp(element);
}


    divs = document.getElementsByClassName('SlctSrch-Inpt');
    for(let i = 0; i < divs.length; i++)
    {
        divs[i].addEventListener('focusin', Functions (){SlctSrch_Inpt_In(this)})
        divs[i].addEventListener('focusout', Functions (){SlctSrch_Inpt_Out(this)})
        divs[i].addEventListener('search', Functions (){SlctSrch_Inpt_Out(this)})
        divs[i].addEventListener('keyup', Functions (){SlctSrch_Inpt_KeyUp(this)});
    }

    divs = document.getElementsByClassName('SlctSrch-Rslt-Elmnt');
    for(let i = 0; i < divs.length; i++)
    {
        divs[i].addEventListener('mouseover', Functions (){SlctSrch_Srch_Elmnt_Int(this)});
        divs[i].addEventListener('mouseout', Functions (){SlctSrch_Srch_Elmnt_Out(this)});
        divs[i].addEventListener('click', Functions (){SlctSrch_Srch_Elmnt_Click(this)});
    }

 */