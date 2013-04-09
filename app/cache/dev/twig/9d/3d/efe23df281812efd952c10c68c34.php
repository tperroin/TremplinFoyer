<?php

/* SonataDoctrineORMAdminBundle:CRUD:edit_orm_one_association_script.html.twig */
class __TwigTemplate_9d3defe23df281812efd952c10c68c34 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 11
        echo "

";
        // line 18
        echo "
";
        // line 20
        echo "
<!-- edit one association -->

<script type=\"text/javascript\">

    // handle the add link
    var field_add_";
        // line 26
        echo $this->getContext($context, "id");
        echo " = function(event) {

        event.preventDefault();
        event.stopPropagation();

        var form = jQuery(this).closest('form');

        // the ajax post
        jQuery(form).ajaxSubmit({
            url: '";
        // line 35
        echo $this->env->getExtension('routing')->getUrl("sonata_admin_append_form_element", (array("code" => $this->getAttribute($this->getAttribute($this->getAttribute($this->getContext($context, "sonata_admin"), "admin"), "root"), "code"), "elementId" => $this->getContext($context, "id"), "objectId" => $this->getAttribute($this->getAttribute($this->getAttribute($this->getContext($context, "sonata_admin"), "admin"), "root"), "id", array(0 => $this->getAttribute($this->getAttribute($this->getAttribute($this->getContext($context, "sonata_admin"), "admin"), "root"), "subject")), "method"), "uniqid" => $this->getAttribute($this->getAttribute($this->getAttribute($this->getContext($context, "sonata_admin"), "admin"), "root"), "uniqid")) + $this->getAttribute($this->getAttribute($this->getContext($context, "sonata_admin"), "field_description"), "getOption", array(0 => "link_parameters", 1 => array()), "method")));
        // line 40
        echo "',
            type: \"POST\",
            dataType: 'html',
            data: { _xml_http_request: true },
            success: function(html) {
                jQuery('#field_container_";
        // line 45
        echo $this->getContext($context, "id");
        echo "').replaceWith(html); // replace the html
                if(jQuery('input[type=\"file\"]', form).length > 0) {
                    jQuery(form).attr('enctype', 'multipart/form-data');
                    jQuery(form).attr('encoding', 'multipart/form-data');
                }
                jQuery('#sonata-ba-field-container-";
        // line 50
        echo $this->getContext($context, "id");
        echo "').trigger('sonata.add_element');
                jQuery('#field_container_";
        // line 51
        echo $this->getContext($context, "id");
        echo "').trigger('sonata.add_element');
            }
        });

        return false;
    };

    var field_widget_";
        // line 58
        echo $this->getContext($context, "id");
        echo " = false;

    // this function initialize the popup
    // this can be only done this way has popup can be cascaded
    function start_field_retrieve_";
        // line 62
        echo $this->getContext($context, "id");
        echo "(link) {

        link.onclick = null;

        // initialize component
        field_widget_";
        // line 67
        echo $this->getContext($context, "id");
        echo " = jQuery(\"#field_widget_";
        echo $this->getContext($context, "id");
        echo "\");

        // add the jQuery event to the a element
        jQuery(link)
            .click(field_add_";
        // line 71
        echo $this->getContext($context, "id");
        echo ")
            .trigger('click')
        ;

        return false;
    }
</script>

<!-- / edit one association -->

";
    }

    public function getTemplateName()
    {
        return "SonataDoctrineORMAdminBundle:CRUD:edit_orm_one_association_script.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  643 => 389,  634 => 383,  630 => 381,  628 => 377,  618 => 374,  597 => 361,  593 => 360,  587 => 357,  567 => 346,  559 => 341,  535 => 326,  504 => 306,  492 => 299,  463 => 284,  458 => 282,  446 => 275,  428 => 265,  420 => 260,  390 => 240,  354 => 219,  281 => 167,  222 => 138,  217 => 136,  297 => 177,  291 => 135,  256 => 122,  251 => 120,  227 => 105,  221 => 102,  215 => 99,  210 => 97,  207 => 129,  194 => 89,  188 => 86,  161 => 75,  129 => 55,  333 => 85,  330 => 84,  325 => 82,  299 => 75,  290 => 72,  288 => 71,  270 => 65,  267 => 64,  261 => 62,  244 => 56,  239 => 112,  231 => 27,  213 => 23,  170 => 89,  159 => 54,  150 => 93,  124 => 47,  113 => 40,  12 => 45,  631 => 203,  625 => 201,  623 => 200,  617 => 196,  608 => 370,  604 => 368,  595 => 190,  591 => 189,  579 => 185,  571 => 183,  568 => 182,  565 => 181,  549 => 334,  545 => 97,  534 => 92,  529 => 321,  526 => 90,  514 => 79,  511 => 310,  505 => 90,  501 => 88,  496 => 301,  493 => 76,  489 => 171,  459 => 159,  442 => 155,  434 => 153,  431 => 152,  415 => 147,  410 => 253,  394 => 242,  332 => 117,  301 => 76,  284 => 110,  265 => 125,  262 => 157,  257 => 74,  249 => 71,  185 => 84,  174 => 51,  613 => 372,  610 => 204,  605 => 199,  598 => 191,  592 => 192,  588 => 190,  586 => 187,  583 => 356,  577 => 353,  564 => 181,  561 => 180,  553 => 177,  550 => 176,  544 => 174,  542 => 330,  539 => 172,  536 => 171,  531 => 324,  527 => 320,  507 => 102,  502 => 122,  499 => 78,  494 => 120,  491 => 119,  485 => 292,  481 => 109,  478 => 108,  470 => 105,  464 => 104,  449 => 100,  441 => 97,  436 => 96,  433 => 95,  424 => 93,  417 => 87,  411 => 86,  406 => 252,  403 => 84,  397 => 48,  389 => 137,  370 => 229,  367 => 39,  361 => 127,  357 => 126,  351 => 32,  347 => 124,  342 => 210,  339 => 28,  336 => 27,  327 => 204,  320 => 171,  316 => 80,  313 => 79,  307 => 166,  304 => 165,  296 => 74,  253 => 147,  248 => 153,  242 => 142,  234 => 140,  232 => 139,  226 => 65,  205 => 95,  198 => 132,  179 => 111,  155 => 68,  127 => 54,  335 => 94,  326 => 90,  306 => 77,  303 => 139,  279 => 68,  276 => 165,  273 => 66,  271 => 163,  259 => 123,  255 => 60,  245 => 66,  240 => 64,  218 => 57,  211 => 53,  206 => 21,  190 => 50,  181 => 115,  172 => 76,  167 => 78,  134 => 42,  65 => 30,  63 => 50,  686 => 206,  680 => 203,  677 => 202,  675 => 201,  669 => 198,  659 => 197,  654 => 195,  642 => 193,  639 => 192,  636 => 205,  627 => 185,  624 => 184,  607 => 182,  590 => 181,  585 => 179,  581 => 178,  578 => 177,  575 => 185,  572 => 184,  566 => 182,  562 => 169,  560 => 100,  555 => 178,  538 => 93,  521 => 317,  517 => 163,  512 => 124,  509 => 161,  506 => 160,  503 => 159,  500 => 158,  498 => 157,  495 => 156,  486 => 151,  482 => 166,  477 => 147,  475 => 146,  472 => 287,  468 => 286,  462 => 123,  456 => 158,  450 => 157,  443 => 140,  432 => 136,  426 => 151,  421 => 131,  416 => 129,  405 => 127,  400 => 248,  391 => 138,  386 => 115,  380 => 112,  377 => 232,  375 => 231,  371 => 109,  366 => 107,  359 => 106,  356 => 105,  353 => 125,  343 => 122,  340 => 97,  331 => 92,  329 => 200,  324 => 113,  321 => 81,  318 => 90,  310 => 184,  302 => 164,  289 => 156,  286 => 85,  282 => 69,  277 => 129,  274 => 77,  272 => 76,  269 => 75,  263 => 71,  254 => 154,  250 => 58,  243 => 65,  238 => 64,  236 => 50,  233 => 146,  228 => 59,  223 => 64,  200 => 92,  197 => 60,  191 => 119,  184 => 114,  178 => 83,  175 => 16,  146 => 46,  118 => 52,  110 => 51,  104 => 49,  90 => 39,  81 => 172,  59 => 23,  23 => 18,  76 => 28,  58 => 12,  53 => 21,  20 => 1,  152 => 67,  148 => 63,  137 => 88,  102 => 41,  100 => 66,  97 => 44,  77 => 58,  34 => 26,  480 => 290,  474 => 164,  469 => 162,  461 => 160,  457 => 153,  453 => 101,  444 => 98,  440 => 272,  437 => 138,  435 => 270,  430 => 94,  427 => 143,  423 => 149,  413 => 146,  409 => 132,  407 => 131,  402 => 143,  398 => 243,  393 => 47,  387 => 122,  384 => 237,  381 => 120,  379 => 236,  374 => 116,  368 => 112,  365 => 111,  362 => 110,  360 => 222,  355 => 106,  341 => 121,  337 => 96,  322 => 201,  314 => 99,  312 => 89,  309 => 97,  305 => 182,  298 => 84,  294 => 136,  285 => 168,  283 => 131,  278 => 86,  268 => 104,  264 => 84,  258 => 81,  252 => 59,  247 => 117,  241 => 149,  235 => 74,  229 => 138,  224 => 71,  220 => 63,  214 => 69,  208 => 136,  169 => 50,  143 => 45,  140 => 56,  132 => 86,  128 => 55,  119 => 49,  107 => 44,  71 => 30,  38 => 32,  177 => 79,  165 => 64,  160 => 70,  135 => 47,  126 => 83,  114 => 46,  84 => 62,  70 => 33,  67 => 51,  61 => 25,  94 => 42,  89 => 40,  85 => 37,  75 => 35,  68 => 49,  56 => 40,  196 => 90,  183 => 84,  171 => 93,  166 => 73,  163 => 49,  158 => 67,  156 => 73,  151 => 72,  142 => 59,  138 => 61,  136 => 60,  121 => 53,  117 => 36,  105 => 26,  91 => 38,  62 => 29,  49 => 21,  87 => 59,  21 => 11,  31 => 22,  25 => 12,  26 => 20,  28 => 14,  24 => 13,  19 => 11,  93 => 43,  88 => 31,  78 => 56,  46 => 35,  44 => 16,  27 => 14,  79 => 34,  72 => 25,  69 => 24,  47 => 17,  40 => 24,  37 => 17,  22 => 12,  246 => 70,  157 => 82,  145 => 63,  139 => 60,  131 => 55,  123 => 54,  120 => 56,  115 => 75,  111 => 48,  108 => 71,  101 => 71,  98 => 47,  96 => 43,  83 => 15,  74 => 31,  66 => 28,  55 => 45,  52 => 20,  50 => 20,  43 => 33,  41 => 18,  35 => 16,  32 => 16,  29 => 21,  209 => 82,  203 => 128,  199 => 20,  193 => 129,  189 => 71,  187 => 118,  182 => 19,  176 => 82,  173 => 110,  168 => 66,  164 => 104,  162 => 62,  154 => 50,  149 => 71,  147 => 58,  144 => 92,  141 => 67,  133 => 57,  130 => 66,  125 => 51,  122 => 50,  116 => 36,  112 => 49,  109 => 47,  106 => 46,  103 => 45,  99 => 23,  95 => 64,  92 => 67,  86 => 39,  82 => 57,  80 => 36,  73 => 27,  64 => 31,  60 => 22,  57 => 22,  54 => 25,  51 => 38,  48 => 40,  45 => 19,  42 => 18,  39 => 16,  36 => 17,  33 => 16,  30 => 15,);
    }
}
