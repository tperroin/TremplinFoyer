<?php

/* SonataAdminBundle:CRUD:delete.html.twig */
class __TwigTemplate_6a711edc63daf08391d4016ddfd7de1d extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->blocks = array(
            'actions' => array($this, 'block_actions'),
            'side_menu' => array($this, 'block_side_menu'),
            'content' => array($this, 'block_content'),
        );
    }

    protected function doGetParent(array $context)
    {
        return $this->env->resolveTemplate($this->getContext($context, "base_template"));
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->getParent($context)->display($context, array_merge($this->blocks, $blocks));
    }

    // line 14
    public function block_actions($context, array $blocks = array())
    {
        // line 15
        echo "    <div class=\"sonata-actions\">
        ";
        // line 16
        if (($this->getAttribute($this->getContext($context, "admin"), "hasRoute", array(0 => "edit"), "method") && $this->getAttribute($this->getContext($context, "admin"), "isGranted", array(0 => "EDIT", 1 => $this->getContext($context, "object")), "method"))) {
            // line 17
            echo "            <a class=\"btn sonata-action-element\" href=\"";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "admin"), "generateObjectUrl", array(0 => "edit", 1 => $this->getContext($context, "object")), "method"), "html", null, true);
            echo "\">
                <i class=\"icon-edit\"></i>
                ";
            // line 19
            echo twig_escape_filter($this->env, $this->env->getExtension('translator')->trans("link_action_edit", array(), "SonataAdminBundle"), "html", null, true);
            echo "</a>
        ";
        }
        // line 21
        echo "        ";
        if (($this->getAttribute($this->getContext($context, "admin"), "hasRoute", array(0 => "create"), "method") && $this->getAttribute($this->getContext($context, "admin"), "isGranted", array(0 => "CREATE"), "method"))) {
            // line 22
            echo "            <a class=\"btn sonata-action-element\" href=\"";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "admin"), "generateUrl", array(0 => "create"), "method"), "html", null, true);
            echo "\">
                <i class=\"icon-plus\"></i>
                ";
            // line 24
            echo twig_escape_filter($this->env, $this->env->getExtension('translator')->trans("link_action_create", array(), "SonataAdminBundle"), "html", null, true);
            echo "</a>
        ";
        }
        // line 26
        echo "    </div>
";
    }

    // line 29
    public function block_side_menu($context, array $blocks = array())
    {
        echo $this->env->getExtension('knp_menu')->render($this->getAttribute($this->getContext($context, "admin"), "sidemenu", array(0 => $this->getContext($context, "action")), "method"), array("currentClass" => "active"), "list");
    }

    // line 31
    public function block_content($context, array $blocks = array())
    {
        // line 32
        echo "    <div class=\"sonata-ba-delete\">

        <h1>";
        // line 34
        echo twig_escape_filter($this->env, $this->env->getExtension('translator')->trans("title_delete", array(), "SonataAdminBundle"), "html", null, true);
        echo "</h1>

        ";
        // line 36
        echo twig_escape_filter($this->env, $this->env->getExtension('translator')->trans("message_delete_confirmation", array("%object%" => $this->getAttribute($this->getContext($context, "admin"), "toString", array(0 => $this->getContext($context, "object")), "method")), "SonataAdminBundle"), "html", null, true);
        echo "

        <div class=\"well form-actions\">
            <form method=\"POST\" action=\"";
        // line 39
        echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "admin"), "generateObjectUrl", array(0 => "delete", 1 => $this->getContext($context, "object")), "method"), "html", null, true);
        echo "\">
                <input type=\"hidden\" value=\"DELETE\" name=\"_method\" />

                <input type=\"submit\" class=\"btn btn-danger\" value=\"";
        // line 42
        echo twig_escape_filter($this->env, $this->env->getExtension('translator')->trans("btn_delete", array(), "SonataAdminBundle"), "html", null, true);
        echo "\" />

                ";
        // line 44
        if (($this->getAttribute($this->getContext($context, "admin"), "hasRoute", array(0 => "edit"), "method") && $this->getAttribute($this->getContext($context, "admin"), "isGranted", array(0 => "EDIT", 1 => $this->getContext($context, "object")), "method"))) {
            // line 45
            echo "                    ";
            echo twig_escape_filter($this->env, $this->env->getExtension('translator')->trans("delete_or", array(), "SonataAdminBundle"), "html", null, true);
            echo "

                    <a class=\"btn btn-success\" href=\"";
            // line 47
            echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "admin"), "generateObjectUrl", array(0 => "edit", 1 => $this->getContext($context, "object")), "method"), "html", null, true);
            echo "\">
                        <i class=\"icon-edit\"></i>
                        ";
            // line 49
            echo twig_escape_filter($this->env, $this->env->getExtension('translator')->trans("link_action_edit", array(), "SonataAdminBundle"), "html", null, true);
            echo "</a>
                ";
        }
        // line 51
        echo "            </form>
        </div>
    </div>
";
    }

    public function getTemplateName()
    {
        return "SonataAdminBundle:CRUD:delete.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  264 => 72,  260 => 71,  226 => 61,  220 => 59,  199 => 52,  188 => 48,  177 => 42,  161 => 46,  156 => 44,  148 => 41,  124 => 47,  113 => 40,  99 => 40,  12 => 45,  638 => 211,  633 => 209,  625 => 206,  619 => 202,  606 => 198,  602 => 197,  591 => 195,  586 => 193,  579 => 191,  571 => 189,  568 => 188,  565 => 187,  545 => 103,  534 => 98,  529 => 97,  526 => 96,  514 => 85,  511 => 84,  507 => 108,  505 => 96,  499 => 84,  496 => 83,  493 => 82,  489 => 177,  469 => 168,  459 => 165,  442 => 161,  413 => 152,  410 => 151,  394 => 145,  389 => 143,  379 => 137,  370 => 134,  357 => 132,  347 => 130,  341 => 127,  301 => 117,  265 => 109,  262 => 82,  249 => 77,  217 => 58,  214 => 65,  210 => 64,  185 => 47,  182 => 46,  174 => 51,  117 => 36,  140 => 56,  97 => 40,  679 => 218,  676 => 217,  671 => 210,  665 => 207,  656 => 203,  651 => 201,  645 => 199,  643 => 198,  640 => 197,  634 => 195,  632 => 194,  629 => 193,  623 => 191,  621 => 190,  618 => 189,  612 => 187,  610 => 199,  604 => 184,  599 => 157,  595 => 196,  589 => 153,  580 => 150,  570 => 148,  567 => 147,  559 => 145,  553 => 111,  549 => 104,  546 => 109,  532 => 105,  524 => 103,  504 => 97,  501 => 94,  492 => 94,  485 => 88,  474 => 170,  471 => 85,  465 => 49,  461 => 166,  457 => 47,  448 => 44,  431 => 158,  425 => 36,  415 => 153,  411 => 31,  403 => 28,  393 => 222,  384 => 140,  382 => 184,  369 => 179,  360 => 175,  355 => 172,  352 => 171,  338 => 170,  332 => 123,  305 => 164,  299 => 161,  291 => 157,  287 => 155,  285 => 145,  278 => 142,  266 => 138,  252 => 69,  241 => 73,  235 => 130,  227 => 128,  222 => 68,  193 => 50,  179 => 112,  166 => 91,  137 => 43,  86 => 181,  335 => 94,  326 => 90,  306 => 87,  303 => 163,  283 => 84,  279 => 115,  276 => 81,  273 => 112,  271 => 79,  268 => 110,  259 => 81,  255 => 70,  245 => 67,  218 => 57,  211 => 56,  206 => 51,  190 => 49,  187 => 49,  169 => 50,  167 => 79,  164 => 85,  134 => 37,  77 => 27,  65 => 5,  56 => 24,  53 => 10,  686 => 206,  680 => 203,  677 => 202,  675 => 201,  669 => 198,  659 => 204,  654 => 202,  642 => 193,  639 => 192,  636 => 191,  627 => 207,  624 => 184,  607 => 185,  590 => 181,  585 => 179,  581 => 178,  578 => 177,  575 => 149,  572 => 175,  566 => 171,  562 => 146,  560 => 106,  555 => 167,  538 => 99,  521 => 102,  517 => 101,  512 => 99,  509 => 98,  506 => 160,  503 => 159,  500 => 158,  498 => 95,  495 => 156,  486 => 151,  482 => 172,  480 => 148,  477 => 147,  475 => 146,  472 => 169,  462 => 123,  453 => 46,  450 => 163,  437 => 138,  435 => 137,  432 => 136,  423 => 155,  421 => 35,  416 => 129,  405 => 127,  402 => 149,  400 => 148,  391 => 144,  377 => 111,  375 => 182,  371 => 109,  366 => 178,  356 => 105,  353 => 131,  343 => 128,  337 => 96,  331 => 92,  329 => 122,  324 => 119,  318 => 90,  312 => 89,  310 => 118,  302 => 86,  298 => 84,  289 => 81,  286 => 85,  274 => 77,  272 => 75,  269 => 74,  254 => 68,  250 => 67,  247 => 66,  243 => 65,  238 => 65,  236 => 63,  233 => 62,  208 => 55,  203 => 62,  200 => 61,  197 => 60,  175 => 45,  173 => 95,  112 => 47,  110 => 29,  90 => 39,  87 => 17,  69 => 17,  49 => 16,  23 => 1,  82 => 29,  62 => 156,  40 => 6,  20 => 11,  479 => 87,  473 => 161,  468 => 125,  460 => 155,  456 => 164,  452 => 151,  443 => 42,  439 => 41,  436 => 147,  434 => 159,  429 => 144,  426 => 157,  422 => 142,  412 => 134,  408 => 132,  406 => 29,  401 => 130,  397 => 129,  392 => 126,  386 => 115,  383 => 121,  380 => 112,  378 => 183,  373 => 116,  367 => 112,  364 => 177,  361 => 133,  359 => 106,  354 => 106,  340 => 97,  336 => 103,  321 => 91,  313 => 99,  311 => 166,  308 => 97,  304 => 95,  297 => 160,  293 => 158,  284 => 116,  282 => 144,  277 => 114,  267 => 85,  263 => 71,  257 => 80,  251 => 80,  246 => 76,  240 => 66,  234 => 63,  228 => 62,  223 => 58,  219 => 67,  213 => 69,  207 => 63,  198 => 123,  181 => 47,  176 => 111,  170 => 61,  168 => 60,  146 => 46,  142 => 56,  128 => 35,  125 => 51,  107 => 24,  38 => 18,  144 => 32,  141 => 51,  135 => 47,  126 => 45,  109 => 47,  103 => 45,  67 => 28,  61 => 26,  47 => 21,  105 => 26,  93 => 41,  76 => 19,  72 => 31,  68 => 24,  225 => 127,  216 => 90,  212 => 88,  205 => 54,  201 => 124,  196 => 122,  194 => 79,  191 => 56,  189 => 77,  186 => 76,  180 => 72,  172 => 16,  159 => 61,  154 => 59,  147 => 58,  132 => 48,  127 => 52,  121 => 33,  118 => 49,  114 => 49,  104 => 42,  100 => 28,  78 => 32,  75 => 32,  71 => 25,  58 => 12,  34 => 16,  27 => 14,  91 => 38,  84 => 36,  44 => 27,  25 => 3,  28 => 14,  24 => 12,  19 => 1,  94 => 39,  88 => 6,  79 => 34,  59 => 155,  31 => 15,  26 => 14,  21 => 11,  70 => 20,  63 => 22,  46 => 9,  22 => 11,  163 => 77,  155 => 82,  152 => 35,  149 => 47,  145 => 46,  139 => 31,  131 => 55,  123 => 52,  120 => 21,  115 => 48,  106 => 44,  101 => 44,  96 => 42,  83 => 35,  80 => 58,  74 => 31,  66 => 29,  55 => 23,  52 => 15,  50 => 22,  43 => 21,  41 => 20,  37 => 5,  35 => 4,  32 => 3,  29 => 2,  184 => 115,  178 => 52,  171 => 94,  165 => 58,  162 => 57,  157 => 83,  153 => 43,  151 => 42,  143 => 45,  138 => 57,  136 => 50,  133 => 54,  130 => 67,  122 => 50,  119 => 51,  116 => 35,  111 => 32,  108 => 44,  102 => 41,  98 => 21,  95 => 39,  92 => 38,  89 => 37,  85 => 36,  81 => 178,  73 => 30,  64 => 27,  60 => 24,  57 => 18,  54 => 22,  51 => 16,  48 => 15,  45 => 14,  42 => 19,  39 => 25,  36 => 17,  33 => 16,  30 => 15,);
    }
}
