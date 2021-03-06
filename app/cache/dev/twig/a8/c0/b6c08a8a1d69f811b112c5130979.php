<?php

/* SonataFormatterBundle:Form:formatter.html.twig */
class __TwigTemplate_a8c0b6c08a8a1d69f811b112c5130979 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
            'sonata_formatter_type_selector_widget' => array($this, 'block_sonata_formatter_type_selector_widget'),
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        $this->displayBlock('sonata_formatter_type_selector_widget', $context, $blocks);
    }

    public function block_sonata_formatter_type_selector_widget($context, array $blocks = array())
    {
        // line 2
        echo "
    ";
        // line 3
        $this->displayBlock("choice_widget", $context, $blocks);
        echo "

    <script>
        var ";
        // line 6
        echo twig_escape_filter($this->env, $this->getContext($context, "source_id"), "html", null, true);
        echo "_rich_instance = false;

        jQuery(document).ready(function() {

            // This code requires CKEDITOR and jQuery MarkItUp
            if (typeof CKEDITOR === 'undefined' || jQuery().markItUp == undefined) {
                return;
            }

            jQuery('#";
        // line 15
        echo twig_escape_filter($this->env, $this->getContext($context, "id"), "html", null, true);
        echo "').change(function() {
                var elms = jQuery('#";
        // line 16
        echo twig_escape_filter($this->env, $this->getContext($context, "source_id"), "html", null, true);
        echo "');
                elms.markItUpRemove();
                if (";
        // line 18
        echo twig_escape_filter($this->env, $this->getContext($context, "source_id"), "html", null, true);
        echo "_rich_instance) {
                    ";
        // line 19
        echo twig_escape_filter($this->env, $this->getContext($context, "source_id"), "html", null, true);
        echo "_rich_instance.destroy();
                }

                var val = jQuery(this).val();
                var appendClass = val;
                switch(val) {
                    case 'textile':
                        elms.markItUp(markitup_sonataTextileSettings);
                        break;
                    case 'markdown':
                        elms.markItUp(markitup_sonataMarkdownSettings);
                        break;
                    case 'bbcode':
                        elms.markItUp(markitup_sonataBBCodeSettings);
                        break;
                    case 'rawhtml':
                        elms.markItUp(markitup_sonataHtmlSettings);
                        appendClass = 'html';
                        break;
                    case 'richhtml':
                        ";
        // line 39
        echo twig_escape_filter($this->env, $this->getContext($context, "source_id"), "html", null, true);
        echo "_rich_instance = CKEDITOR.replace('";
        echo twig_escape_filter($this->env, $this->getContext($context, "source_id"), "html", null, true);
        echo "', {
                            toolbar: [
                                [ 'Bold','Italic','Underline',
                                  '-', 'Cut','Copy','Paste','PasteText','PasteFromWord',
                                  '-','Undo','Redo',
                                  '-', 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote',
                                  '-', 'Image', 'Link','Unlink'
                                ], [
                                  'Maximize', 'Source'
                                ]
                            ]
                        });
                }

                var parent = elms.parents('div.markItUp');

                if (parent) {
                    for (name in ['textile', 'markdown', 'bbcode', 'rawhtml', 'richhtml', 'rawhtml']) {
                        parent.removeClass(name)
                    }

                    parent.addClass(appendClass);
                }
            });

            jQuery('#";
        // line 64
        echo twig_escape_filter($this->env, $this->getContext($context, "id"), "html", null, true);
        echo "').trigger('change');
        });
    </script>
";
    }

    public function getTemplateName()
    {
        return "SonataFormatterBundle:Form:formatter.html.twig";
    }

    public function getDebugInfo()
    {
        return array (  229 => 96,  244 => 84,  192 => 66,  224 => 72,  388 => 154,  381 => 150,  365 => 142,  351 => 137,  346 => 136,  339 => 133,  323 => 124,  319 => 123,  315 => 122,  288 => 109,  270 => 100,  261 => 96,  239 => 82,  231 => 79,  215 => 76,  630 => 381,  628 => 377,  613 => 372,  608 => 370,  597 => 361,  593 => 360,  587 => 357,  583 => 356,  577 => 353,  542 => 330,  535 => 326,  531 => 324,  527 => 320,  463 => 284,  458 => 282,  446 => 275,  440 => 272,  428 => 265,  420 => 260,  398 => 243,  390 => 155,  342 => 134,  248 => 89,  183 => 83,  158 => 64,  295 => 135,  292 => 134,  281 => 167,  275 => 127,  237 => 110,  221 => 78,  202 => 72,  150 => 21,  160 => 18,  129 => 51,  264 => 98,  260 => 71,  226 => 81,  220 => 59,  199 => 52,  188 => 48,  177 => 72,  161 => 56,  156 => 67,  148 => 65,  124 => 51,  113 => 64,  99 => 38,  12 => 45,  638 => 211,  633 => 209,  625 => 206,  619 => 202,  606 => 198,  602 => 197,  591 => 195,  586 => 193,  579 => 191,  571 => 189,  568 => 188,  565 => 187,  545 => 103,  534 => 98,  529 => 321,  526 => 96,  514 => 85,  511 => 310,  507 => 108,  505 => 96,  499 => 84,  496 => 301,  493 => 82,  489 => 177,  469 => 168,  459 => 165,  442 => 161,  413 => 152,  410 => 253,  394 => 242,  389 => 143,  379 => 236,  370 => 229,  357 => 132,  347 => 130,  341 => 127,  301 => 137,  265 => 109,  262 => 157,  249 => 118,  217 => 136,  214 => 65,  210 => 64,  185 => 64,  182 => 82,  174 => 23,  117 => 39,  140 => 58,  97 => 91,  679 => 218,  676 => 217,  671 => 210,  665 => 207,  656 => 203,  651 => 201,  645 => 199,  643 => 389,  640 => 197,  634 => 383,  632 => 194,  629 => 193,  623 => 191,  621 => 190,  618 => 374,  612 => 187,  610 => 199,  604 => 368,  599 => 157,  595 => 196,  589 => 153,  580 => 150,  570 => 148,  567 => 346,  559 => 341,  553 => 111,  549 => 334,  546 => 109,  532 => 105,  524 => 103,  504 => 306,  501 => 94,  492 => 299,  485 => 292,  474 => 170,  471 => 85,  465 => 49,  461 => 166,  457 => 47,  448 => 44,  431 => 158,  425 => 36,  415 => 153,  411 => 31,  403 => 28,  393 => 156,  384 => 237,  382 => 184,  369 => 179,  360 => 140,  355 => 172,  352 => 171,  338 => 170,  332 => 123,  305 => 182,  299 => 161,  291 => 157,  287 => 155,  285 => 168,  278 => 142,  266 => 138,  252 => 119,  241 => 101,  235 => 130,  227 => 105,  222 => 138,  193 => 47,  179 => 61,  166 => 91,  137 => 56,  86 => 31,  335 => 94,  326 => 90,  306 => 87,  303 => 163,  283 => 107,  279 => 115,  276 => 165,  273 => 112,  271 => 163,  268 => 110,  259 => 81,  255 => 70,  245 => 115,  218 => 91,  211 => 98,  206 => 55,  190 => 69,  187 => 68,  169 => 80,  167 => 71,  164 => 19,  134 => 55,  77 => 28,  65 => 24,  56 => 18,  53 => 23,  686 => 206,  680 => 203,  677 => 202,  675 => 201,  669 => 198,  659 => 204,  654 => 202,  642 => 193,  639 => 192,  636 => 191,  627 => 207,  624 => 184,  607 => 185,  590 => 181,  585 => 179,  581 => 178,  578 => 177,  575 => 149,  572 => 175,  566 => 171,  562 => 146,  560 => 106,  555 => 167,  538 => 99,  521 => 317,  517 => 101,  512 => 99,  509 => 98,  506 => 160,  503 => 159,  500 => 158,  498 => 95,  495 => 156,  486 => 151,  482 => 172,  480 => 290,  477 => 147,  475 => 146,  472 => 287,  462 => 123,  453 => 46,  450 => 163,  437 => 138,  435 => 270,  432 => 136,  423 => 155,  421 => 35,  416 => 129,  405 => 127,  402 => 149,  400 => 248,  391 => 144,  377 => 148,  375 => 231,  371 => 145,  366 => 178,  356 => 139,  353 => 138,  343 => 128,  337 => 96,  331 => 92,  329 => 127,  324 => 119,  318 => 90,  312 => 89,  310 => 184,  302 => 116,  298 => 84,  289 => 133,  286 => 85,  274 => 77,  272 => 75,  269 => 125,  254 => 92,  250 => 67,  247 => 66,  243 => 87,  238 => 65,  236 => 63,  233 => 98,  208 => 70,  203 => 54,  200 => 61,  197 => 68,  175 => 60,  173 => 71,  112 => 42,  110 => 46,  90 => 38,  87 => 34,  69 => 30,  49 => 21,  23 => 13,  82 => 39,  62 => 25,  40 => 21,  20 => 1,  479 => 87,  473 => 161,  468 => 286,  460 => 155,  456 => 164,  452 => 151,  443 => 42,  439 => 41,  436 => 147,  434 => 159,  429 => 144,  426 => 157,  422 => 142,  412 => 134,  408 => 132,  406 => 252,  401 => 130,  397 => 129,  392 => 126,  386 => 115,  383 => 121,  380 => 112,  378 => 183,  373 => 116,  367 => 112,  364 => 177,  361 => 133,  359 => 106,  354 => 219,  340 => 97,  336 => 131,  321 => 91,  313 => 99,  311 => 166,  308 => 97,  304 => 95,  297 => 177,  293 => 158,  284 => 116,  282 => 144,  277 => 104,  267 => 99,  263 => 123,  257 => 121,  251 => 105,  246 => 76,  240 => 66,  234 => 63,  228 => 82,  223 => 58,  219 => 67,  213 => 99,  207 => 129,  198 => 80,  181 => 44,  176 => 62,  170 => 22,  168 => 69,  146 => 49,  142 => 29,  128 => 44,  125 => 51,  107 => 40,  38 => 15,  144 => 54,  141 => 58,  135 => 55,  126 => 56,  109 => 44,  103 => 39,  67 => 27,  61 => 31,  47 => 15,  105 => 9,  93 => 37,  76 => 36,  72 => 28,  68 => 25,  225 => 95,  216 => 72,  212 => 88,  205 => 84,  201 => 69,  196 => 71,  194 => 79,  191 => 119,  189 => 46,  186 => 88,  180 => 72,  172 => 16,  159 => 61,  154 => 66,  147 => 61,  132 => 108,  127 => 44,  121 => 54,  118 => 103,  114 => 102,  104 => 43,  100 => 94,  78 => 31,  75 => 30,  71 => 26,  58 => 23,  34 => 14,  27 => 14,  91 => 32,  84 => 35,  44 => 18,  25 => 14,  28 => 13,  24 => 14,  19 => 11,  94 => 36,  88 => 31,  79 => 33,  59 => 22,  31 => 14,  26 => 2,  21 => 12,  70 => 22,  63 => 23,  46 => 20,  22 => 12,  163 => 66,  155 => 51,  152 => 50,  149 => 53,  145 => 60,  139 => 57,  131 => 22,  123 => 43,  120 => 43,  115 => 75,  106 => 48,  101 => 45,  96 => 35,  83 => 39,  80 => 34,  74 => 31,  66 => 53,  55 => 21,  52 => 24,  50 => 22,  43 => 8,  41 => 19,  37 => 18,  35 => 6,  32 => 15,  29 => 3,  184 => 74,  178 => 52,  171 => 61,  165 => 58,  162 => 69,  157 => 17,  153 => 63,  151 => 42,  143 => 59,  138 => 47,  136 => 109,  133 => 54,  130 => 54,  122 => 104,  119 => 12,  116 => 47,  111 => 39,  108 => 99,  102 => 37,  98 => 47,  95 => 64,  92 => 44,  89 => 32,  85 => 31,  81 => 32,  73 => 39,  64 => 26,  60 => 19,  57 => 25,  54 => 22,  51 => 16,  48 => 23,  45 => 19,  42 => 18,  39 => 18,  36 => 17,  33 => 15,  30 => 16,);
    }
}
