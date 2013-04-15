<?php

/* SonataMediaBundle:Extra:pixlr_editor.html.twig */
class __TwigTemplate_fdcd35114314736b8112bcf0f8afe84f extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
            'stylesheets' => array($this, 'block_stylesheets'),
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 11
        echo "<!DOCTYPE html>
<html class=\"no-js\">
    <head>
        <meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />

        ";
        // line 16
        $this->displayBlock('stylesheets', $context, $blocks);
        // line 25
        echo "
        <title>Pixlr Editor</title>

        <style>
            .header {
                text-align: center;
                background: none repeat scroll 0 0 #222222;
                border-bottom: 5px solid #B6B6B6;
                padding: 15px 0;
            }

            body.sonata-bc {
               padding-top: 0;
               margin: 0px;
               padding: 0px;
            }

            a.box {
                text-align: center;
                width: 120px;
                float: left;
                margin: 30px;
                margin-left: 35px;
                margin-right: 35px;

                padding: 30px;
                text-decoration: none;

                color: #B6B6B6;
                -webkit-border-radius: 20px;
                -moz-border-radius: 20px;
                border-radius: 20px;
                border:9px solid #B6B6B6;
                background-color:#222222;
                /*-webkit-box-shadow: #B3B3B3 3px 3px 3px;*/
                /*-moz-box-shadow: #B3B3B3 3px 3px 3px;*/
                /*box-shadow: #B3B3B3 3px 3px 3px*/
            }

            a.box:hover {
                color: #222222;
                -webkit-border-radius: 20px;
                -moz-border-radius: 20px;
                border-radius: 20px;
                border:9px solid #222222;
                background-color:#B6B6B6;
                text-decoration: none;
                /*-webkit-box-shadow: #B3B3B3 3px 3px 3px;*/
                /*-moz-box-shadow: #B3B3B3 3px 3px 3px;*/
                /*box-shadow: #B3B3B3 3px 3px 3px*/
            }

            div.content {
                text-align: center;
                width: 550px;
                padding: 30px;
                margin-left: auto;
                margin-right: auto;
            }

            .footer {
                clear: both;
                text-align: center;
            }
        </style>
    </head>

    <body class=\"sonata-bc\">
        <div class=\"header\">
            <img src=\"";
        // line 94
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("bundles/sonatamedia/extra/pixlr/pixlr.png"), "html", null, true);
        echo "\" alt=\"Pixlr\"/>
        </div>

        <div class=\"content\">
            <div class=\"alert-message block-message warning\">
                ";
        // line 99
        echo $this->env->getExtension('translator')->trans("label.pixlr_warning", array(), "SonataMediaBundle");
        echo "
            </div>

            <a href=\"";
        // line 102
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getUrl("sonata_media_pixlr_edit", array("id" => $this->env->getExtension('sonata_admin')->getUrlsafeIdentifier($this->getContext($context, "media")), "mode" => "express")), "html", null, true);
        echo "\" class=\"box\">
                <img src=\"";
        // line 103
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("bundles/sonatamedia/extra/pixlr/express_128.png"), "html", null, true);
        echo "\" alt=\"Pixlr Express\" style=\"margin-left: -5px;\"/>
                ";
        // line 104
        echo $this->env->getExtension('translator')->trans("label.pixlr_express_editor", array(), "SonataMediaBundle");
        echo "
            </a>

            <a href=\"";
        // line 107
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getUrl("sonata_media_pixlr_edit", array("id" => $this->env->getExtension('sonata_admin')->getUrlsafeIdentifier($this->getContext($context, "media")), "mode" => "editor")), "html", null, true);
        echo "\" class=\"box\">
                <img src=\"";
        // line 108
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("bundles/sonatamedia/extra/pixlr/editor_128.png"), "html", null, true);
        echo "\" alt=\"Pixlr Editor\" style=\"margin-left: -5px;\"/>
                ";
        // line 109
        echo $this->env->getExtension('translator')->trans("label.pixlr_advanced_editor", array(), "SonataMediaBundle");
        echo "
            </a>

            <div style=\"clear: both\"></div>
        </div>

        <div class=\"footer\">
            The Sonata Project is not linked to Pixlr and does not provide supports on the Pixlr service. <br />
            <a href=\"http://pixlr.com\" target=\"_blank\">Pixlr</a> is an external web service provided by <a href=\"http://usa.autodesk.com/adsk/servlet/pc/index?id=17483004&siteID=123112\" target=\"_blank\">Autodesk</a><br>
            <a href=\"http://pixlr.com/terms_of_service/\" target=\"_blank\">Pixlr's Terms of Service</a> and <a href=\"http://pixlr.com/privacy_policy/\" target=\"_blank\">Pixlr's Privacy Policy</a> <br />
        </div>
    </body>
</html>
";
    }

    // line 16
    public function block_stylesheets($context, array $blocks = array())
    {
        // line 17
        echo "            <!-- jQuery code -->
            <link rel=\"stylesheet\" href=\"";
        // line 18
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("bundles/sonatajquery/themes/flick/jquery-ui-1.8.16.custom.css"), "html", null, true);
        echo "\" type=\"text/css\" media=\"all\" />
            <link rel=\"stylesheet\" href=\"";
        // line 19
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("bundles/sonataadmin/bootstrap/css/bootstrap.min.css"), "html", null, true);
        echo "\" type=\"text/css\" media=\"all\" >

            <!-- base application asset -->
            <link rel=\"stylesheet\" href=\"";
        // line 22
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("bundles/sonataadmin/css/layout.css"), "html", null, true);
        echo "\" type=\"text/css\" media=\"all\">
            <link rel=\"stylesheet\" href=\"";
        // line 23
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("bundles/sonataadmin/css/colors.css"), "html", null, true);
        echo "\" type=\"text/css\" media=\"all\">
        ";
    }

    public function getTemplateName()
    {
        return "SonataMediaBundle:Extra:pixlr_editor.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  229 => 96,  244 => 84,  192 => 66,  224 => 72,  388 => 154,  381 => 150,  365 => 142,  351 => 137,  346 => 136,  339 => 133,  323 => 124,  319 => 123,  315 => 122,  288 => 109,  270 => 100,  261 => 96,  239 => 82,  231 => 79,  215 => 76,  630 => 381,  628 => 377,  613 => 372,  608 => 370,  597 => 361,  593 => 360,  587 => 357,  583 => 356,  577 => 353,  542 => 330,  535 => 326,  531 => 324,  527 => 320,  463 => 284,  458 => 282,  446 => 275,  440 => 272,  428 => 265,  420 => 260,  398 => 243,  390 => 155,  342 => 134,  248 => 89,  183 => 83,  158 => 64,  295 => 135,  292 => 134,  281 => 167,  275 => 127,  237 => 110,  221 => 78,  202 => 72,  150 => 21,  160 => 18,  129 => 45,  264 => 98,  260 => 71,  226 => 81,  220 => 59,  199 => 52,  188 => 48,  177 => 72,  161 => 56,  156 => 55,  148 => 65,  124 => 51,  113 => 40,  99 => 23,  12 => 45,  638 => 211,  633 => 209,  625 => 206,  619 => 202,  606 => 198,  602 => 197,  591 => 195,  586 => 193,  579 => 191,  571 => 189,  568 => 188,  565 => 187,  545 => 103,  534 => 98,  529 => 321,  526 => 96,  514 => 85,  511 => 310,  507 => 108,  505 => 96,  499 => 84,  496 => 301,  493 => 82,  489 => 177,  469 => 168,  459 => 165,  442 => 161,  413 => 152,  410 => 253,  394 => 242,  389 => 143,  379 => 236,  370 => 229,  357 => 132,  347 => 130,  341 => 127,  301 => 137,  265 => 109,  262 => 157,  249 => 118,  217 => 136,  214 => 65,  210 => 64,  185 => 64,  182 => 82,  174 => 23,  117 => 39,  140 => 58,  97 => 91,  679 => 218,  676 => 217,  671 => 210,  665 => 207,  656 => 203,  651 => 201,  645 => 199,  643 => 389,  640 => 197,  634 => 383,  632 => 194,  629 => 193,  623 => 191,  621 => 190,  618 => 374,  612 => 187,  610 => 199,  604 => 368,  599 => 157,  595 => 196,  589 => 153,  580 => 150,  570 => 148,  567 => 346,  559 => 341,  553 => 111,  549 => 334,  546 => 109,  532 => 105,  524 => 103,  504 => 306,  501 => 94,  492 => 299,  485 => 292,  474 => 170,  471 => 85,  465 => 49,  461 => 166,  457 => 47,  448 => 44,  431 => 158,  425 => 36,  415 => 153,  411 => 31,  403 => 28,  393 => 156,  384 => 237,  382 => 184,  369 => 179,  360 => 140,  355 => 172,  352 => 171,  338 => 170,  332 => 123,  305 => 182,  299 => 161,  291 => 157,  287 => 155,  285 => 168,  278 => 142,  266 => 138,  252 => 119,  241 => 101,  235 => 130,  227 => 105,  222 => 138,  193 => 47,  179 => 61,  166 => 91,  137 => 88,  86 => 31,  335 => 94,  326 => 90,  306 => 87,  303 => 163,  283 => 107,  279 => 115,  276 => 165,  273 => 112,  271 => 163,  268 => 110,  259 => 81,  255 => 70,  245 => 115,  218 => 91,  211 => 98,  206 => 55,  190 => 69,  187 => 68,  169 => 80,  167 => 58,  164 => 19,  134 => 55,  77 => 33,  65 => 28,  56 => 21,  53 => 22,  686 => 206,  680 => 203,  677 => 202,  675 => 201,  669 => 198,  659 => 204,  654 => 202,  642 => 193,  639 => 192,  636 => 191,  627 => 207,  624 => 184,  607 => 185,  590 => 181,  585 => 179,  581 => 178,  578 => 177,  575 => 149,  572 => 175,  566 => 171,  562 => 146,  560 => 106,  555 => 167,  538 => 99,  521 => 317,  517 => 101,  512 => 99,  509 => 98,  506 => 160,  503 => 159,  500 => 158,  498 => 95,  495 => 156,  486 => 151,  482 => 172,  480 => 290,  477 => 147,  475 => 146,  472 => 287,  462 => 123,  453 => 46,  450 => 163,  437 => 138,  435 => 270,  432 => 136,  423 => 155,  421 => 35,  416 => 129,  405 => 127,  402 => 149,  400 => 248,  391 => 144,  377 => 148,  375 => 231,  371 => 145,  366 => 178,  356 => 139,  353 => 138,  343 => 128,  337 => 96,  331 => 92,  329 => 127,  324 => 119,  318 => 90,  312 => 89,  310 => 184,  302 => 116,  298 => 84,  289 => 133,  286 => 85,  274 => 77,  272 => 75,  269 => 125,  254 => 92,  250 => 67,  247 => 66,  243 => 87,  238 => 65,  236 => 63,  233 => 98,  208 => 70,  203 => 54,  200 => 61,  197 => 68,  175 => 60,  173 => 71,  112 => 40,  110 => 46,  90 => 38,  87 => 41,  69 => 30,  49 => 12,  23 => 1,  82 => 39,  62 => 25,  40 => 18,  20 => 11,  479 => 87,  473 => 161,  468 => 286,  460 => 155,  456 => 164,  452 => 151,  443 => 42,  439 => 41,  436 => 147,  434 => 159,  429 => 144,  426 => 157,  422 => 142,  412 => 134,  408 => 132,  406 => 252,  401 => 130,  397 => 129,  392 => 126,  386 => 115,  383 => 121,  380 => 112,  378 => 183,  373 => 116,  367 => 112,  364 => 177,  361 => 133,  359 => 106,  354 => 219,  340 => 97,  336 => 131,  321 => 91,  313 => 99,  311 => 166,  308 => 97,  304 => 95,  297 => 177,  293 => 158,  284 => 116,  282 => 144,  277 => 104,  267 => 99,  263 => 123,  257 => 121,  251 => 105,  246 => 76,  240 => 66,  234 => 63,  228 => 82,  223 => 58,  219 => 67,  213 => 99,  207 => 129,  198 => 80,  181 => 44,  176 => 62,  170 => 22,  168 => 69,  146 => 49,  142 => 29,  128 => 107,  125 => 51,  107 => 44,  38 => 17,  144 => 54,  141 => 19,  135 => 50,  126 => 56,  109 => 44,  103 => 45,  67 => 27,  61 => 31,  47 => 20,  105 => 9,  93 => 43,  76 => 36,  72 => 28,  68 => 9,  225 => 95,  216 => 72,  212 => 88,  205 => 84,  201 => 69,  196 => 71,  194 => 79,  191 => 119,  189 => 46,  186 => 88,  180 => 72,  172 => 16,  159 => 61,  154 => 16,  147 => 61,  132 => 108,  127 => 44,  121 => 54,  118 => 103,  114 => 102,  104 => 43,  100 => 94,  78 => 20,  75 => 14,  71 => 70,  58 => 23,  34 => 16,  27 => 16,  91 => 33,  84 => 35,  44 => 19,  25 => 14,  28 => 14,  24 => 14,  19 => 2,  94 => 39,  88 => 31,  79 => 31,  59 => 24,  31 => 15,  26 => 1,  21 => 12,  70 => 22,  63 => 27,  46 => 19,  22 => 14,  163 => 66,  155 => 51,  152 => 50,  149 => 53,  145 => 60,  139 => 61,  131 => 22,  123 => 43,  120 => 50,  115 => 75,  106 => 48,  101 => 45,  96 => 35,  83 => 15,  80 => 34,  74 => 15,  66 => 53,  55 => 58,  52 => 27,  50 => 21,  43 => 8,  41 => 18,  37 => 18,  35 => 16,  32 => 15,  29 => 25,  184 => 74,  178 => 52,  171 => 61,  165 => 58,  162 => 57,  157 => 17,  153 => 63,  151 => 42,  143 => 45,  138 => 47,  136 => 109,  133 => 58,  130 => 54,  122 => 104,  119 => 12,  116 => 11,  111 => 39,  108 => 99,  102 => 37,  98 => 47,  95 => 64,  92 => 44,  89 => 32,  85 => 38,  81 => 15,  73 => 39,  64 => 26,  60 => 6,  57 => 24,  54 => 12,  51 => 21,  48 => 22,  45 => 21,  42 => 10,  39 => 19,  36 => 17,  33 => 6,  30 => 15,);
    }
}
