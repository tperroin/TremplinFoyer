<?php

/* SonataAdminBundle:CRUD:action.html.twig */
class __TwigTemplate_62fd3bbf9185494ab6632d3863f12dda extends Twig_Template
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
        echo "    <div class=\"sonata-actions btn-group\">
        ";
        // line 16
        if (($this->getAttribute($this->getContext($context, "admin"), "hasRoute", array(0 => "create"), "method") && $this->getAttribute($this->getContext($context, "admin"), "isGranted", array(0 => "CREATE"), "method"))) {
            // line 17
            echo "            <a class=\"btn sonata-action-element\" href=\"";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "admin"), "generateUrl", array(0 => "create"), "method"), "html", null, true);
            echo "\">
                <i class=\"icon-plus\"></i>
                ";
            // line 19
            echo twig_escape_filter($this->env, $this->env->getExtension('translator')->trans("link_action_create", array(), "SonataAdminBundle"), "html", null, true);
            echo "</a>
        ";
        }
        // line 21
        echo "        ";
        if (($this->getAttribute($this->getContext($context, "admin"), "hasRoute", array(0 => "list"), "method") && $this->getAttribute($this->getContext($context, "admin"), "isGranted", array(0 => "LIST"), "method"))) {
            // line 22
            echo "            <a class=\"btn sonata-action-element\" href=\"";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "admin"), "generateUrl", array(0 => "list"), "method"), "html", null, true);
            echo "\">
                <i class=\"icon-list\"></i>
                ";
            // line 24
            echo twig_escape_filter($this->env, $this->env->getExtension('translator')->trans("link_action_list", array(), "SonataAdminBundle"), "html", null, true);
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
        if (array_key_exists("action", $context)) {
            echo $this->env->getExtension('knp_menu')->render($this->getAttribute($this->getContext($context, "admin"), "sidemenu", array(0 => $this->getContext($context, "action")), "method"), array("currentClass" => "active"), "list");
        }
    }

    // line 31
    public function block_content($context, array $blocks = array())
    {
        // line 32
        echo "
    Redefine the content block in your action template

";
    }

    public function getTemplateName()
    {
        return "SonataAdminBundle:CRUD:action.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  631 => 203,  625 => 201,  623 => 200,  617 => 196,  608 => 193,  604 => 192,  595 => 190,  591 => 189,  579 => 185,  571 => 183,  568 => 182,  565 => 181,  549 => 98,  545 => 97,  534 => 92,  529 => 91,  526 => 90,  514 => 79,  511 => 78,  505 => 90,  501 => 88,  496 => 77,  493 => 76,  489 => 171,  459 => 159,  442 => 155,  434 => 153,  431 => 152,  415 => 147,  410 => 145,  394 => 139,  332 => 117,  301 => 111,  284 => 110,  265 => 103,  262 => 76,  257 => 74,  249 => 71,  185 => 55,  174 => 51,  613 => 205,  610 => 204,  605 => 199,  598 => 191,  592 => 192,  588 => 190,  586 => 187,  583 => 188,  577 => 186,  564 => 181,  561 => 180,  553 => 177,  550 => 176,  544 => 174,  542 => 173,  539 => 172,  536 => 171,  531 => 159,  527 => 128,  507 => 102,  502 => 122,  499 => 78,  494 => 120,  491 => 119,  485 => 110,  481 => 109,  478 => 108,  470 => 105,  464 => 104,  449 => 100,  441 => 97,  436 => 96,  433 => 95,  424 => 93,  417 => 87,  411 => 86,  406 => 85,  403 => 84,  397 => 48,  389 => 137,  370 => 128,  367 => 39,  361 => 127,  357 => 126,  351 => 32,  347 => 124,  342 => 29,  339 => 28,  336 => 27,  327 => 204,  320 => 171,  316 => 170,  313 => 169,  307 => 166,  304 => 165,  296 => 160,  253 => 147,  248 => 144,  242 => 142,  234 => 140,  232 => 139,  226 => 65,  205 => 135,  198 => 132,  179 => 111,  155 => 81,  127 => 52,  335 => 94,  326 => 90,  306 => 87,  303 => 86,  279 => 109,  276 => 151,  273 => 106,  271 => 79,  259 => 75,  255 => 69,  245 => 66,  240 => 64,  218 => 57,  211 => 53,  206 => 51,  190 => 50,  181 => 47,  172 => 44,  167 => 42,  134 => 42,  65 => 5,  63 => 25,  686 => 206,  680 => 203,  677 => 202,  675 => 201,  669 => 198,  659 => 197,  654 => 195,  642 => 193,  639 => 192,  636 => 205,  627 => 185,  624 => 184,  607 => 182,  590 => 181,  585 => 179,  581 => 178,  578 => 177,  575 => 185,  572 => 184,  566 => 182,  562 => 169,  560 => 100,  555 => 178,  538 => 93,  521 => 127,  517 => 163,  512 => 124,  509 => 161,  506 => 160,  503 => 159,  500 => 158,  498 => 157,  495 => 156,  486 => 151,  482 => 166,  477 => 147,  475 => 146,  472 => 163,  468 => 125,  462 => 123,  456 => 158,  450 => 157,  443 => 140,  432 => 136,  426 => 151,  421 => 131,  416 => 129,  405 => 127,  400 => 142,  391 => 138,  386 => 115,  380 => 112,  377 => 111,  375 => 41,  371 => 109,  366 => 107,  359 => 106,  356 => 105,  353 => 125,  343 => 122,  340 => 97,  331 => 92,  329 => 116,  324 => 113,  321 => 91,  318 => 90,  310 => 112,  302 => 164,  289 => 156,  286 => 85,  282 => 79,  277 => 108,  274 => 77,  272 => 76,  269 => 75,  263 => 71,  254 => 68,  250 => 67,  243 => 65,  238 => 64,  236 => 63,  233 => 62,  228 => 59,  223 => 64,  200 => 61,  197 => 60,  191 => 56,  184 => 114,  178 => 52,  175 => 45,  146 => 46,  118 => 28,  110 => 63,  104 => 30,  90 => 18,  81 => 172,  59 => 155,  23 => 12,  76 => 69,  58 => 38,  53 => 77,  20 => 11,  152 => 35,  148 => 63,  137 => 43,  102 => 41,  100 => 28,  97 => 27,  77 => 32,  34 => 16,  480 => 148,  474 => 164,  469 => 162,  461 => 160,  457 => 153,  453 => 101,  444 => 98,  440 => 148,  437 => 138,  435 => 137,  430 => 94,  427 => 143,  423 => 149,  413 => 146,  409 => 132,  407 => 131,  402 => 143,  398 => 129,  393 => 47,  387 => 122,  384 => 134,  381 => 120,  379 => 131,  374 => 116,  368 => 112,  365 => 111,  362 => 110,  360 => 109,  355 => 106,  341 => 121,  337 => 96,  322 => 201,  314 => 99,  312 => 89,  309 => 97,  305 => 95,  298 => 84,  294 => 159,  285 => 154,  283 => 84,  278 => 86,  268 => 104,  264 => 84,  258 => 81,  252 => 72,  247 => 66,  241 => 67,  235 => 74,  229 => 138,  224 => 71,  220 => 63,  214 => 69,  208 => 136,  169 => 50,  143 => 45,  140 => 44,  132 => 51,  128 => 40,  119 => 37,  107 => 24,  71 => 59,  38 => 15,  177 => 65,  165 => 64,  160 => 66,  135 => 47,  126 => 51,  114 => 42,  84 => 16,  70 => 20,  67 => 52,  61 => 26,  94 => 38,  89 => 176,  85 => 25,  75 => 21,  68 => 6,  56 => 24,  196 => 90,  183 => 70,  171 => 93,  166 => 90,  163 => 49,  158 => 67,  156 => 65,  151 => 48,  142 => 59,  138 => 57,  136 => 56,  121 => 29,  117 => 36,  105 => 40,  91 => 31,  62 => 156,  49 => 23,  87 => 17,  21 => 11,  31 => 15,  25 => 13,  26 => 14,  28 => 14,  24 => 13,  19 => 11,  93 => 41,  88 => 6,  78 => 21,  46 => 21,  44 => 74,  27 => 14,  79 => 70,  72 => 54,  69 => 27,  47 => 21,  40 => 13,  37 => 23,  22 => 11,  246 => 70,  157 => 82,  145 => 46,  139 => 31,  131 => 41,  123 => 51,  120 => 21,  115 => 49,  111 => 32,  108 => 31,  101 => 43,  98 => 21,  96 => 31,  83 => 58,  74 => 31,  66 => 29,  55 => 21,  52 => 24,  50 => 22,  43 => 17,  41 => 16,  35 => 14,  32 => 14,  29 => 15,  209 => 82,  203 => 62,  199 => 67,  193 => 129,  189 => 71,  187 => 49,  182 => 54,  176 => 110,  173 => 94,  168 => 66,  164 => 84,  162 => 62,  154 => 54,  149 => 47,  147 => 33,  144 => 32,  141 => 61,  133 => 67,  130 => 66,  125 => 39,  122 => 38,  116 => 36,  112 => 48,  109 => 18,  106 => 61,  103 => 60,  99 => 30,  95 => 20,  92 => 19,  86 => 175,  82 => 35,  80 => 25,  73 => 7,  64 => 174,  60 => 24,  57 => 23,  54 => 22,  51 => 14,  48 => 20,  45 => 19,  42 => 19,  39 => 17,  36 => 17,  33 => 16,  30 => 15,);
    }
}
