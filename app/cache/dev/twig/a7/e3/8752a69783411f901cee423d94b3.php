<?php

/* SonataUserBundle:Profile:edit_profile.html.twig */
class __TwigTemplate_a7e38752a69783411f901cee423d94b3 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
            'field_row' => array($this, 'block_field_row'),
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 11
        echo "
<div class=\"sonata-user-edit-profile\">

    <h2>";
        // line 14
        echo twig_escape_filter($this->env, $this->env->getExtension('translator')->trans("title_user_account", array(), "SonataUserBundle"), "html", null, true);
        echo " - ";
        echo twig_escape_filter($this->env, $this->env->getExtension('translator')->trans("title_user_edit_profile", array(), "SonataUserBundle"), "html", null, true);
        echo "</h2>

    ";
        // line 16
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getContext($context, "form"), 'errors');
        echo "

    ";
        // line 18
        $this->env->getExtension('form')->renderer->setTheme($this->getContext($context, "form"), array(0 => $this));
        // line 19
        echo "
    ";
        // line 20
        $this->displayBlock('field_row', $context, $blocks);
        // line 24
        echo "
    <form action=\"";
        // line 25
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("sonata_user_profile_edit"), "html", null, true);
        echo "\" method=\"POST\" class=\"form-horizontal\">
        <div class=\"control-group\">
            ";
        // line 27
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute($this->getContext($context, "form"), "gender"), 'label', array("attr" => array("class" => "control-label"), "label" => "label_profile_gender"));
        echo "
            <div class=\"controls\">";
        // line 28
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute($this->getContext($context, "form"), "gender"), 'widget');
        echo "</div>
        </div>
        <div class=\"control-group\">
            ";
        // line 31
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute($this->getContext($context, "form"), "firstname"), 'label', array("attr" => array("class" => "control-label"), "label" => "label_profile_firstname"));
        echo "
            <div class=\"controls\">";
        // line 32
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute($this->getContext($context, "form"), "firstname"), 'widget');
        echo "</div>
        </div>
        <div class=\"control-group\">
            ";
        // line 35
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute($this->getContext($context, "form"), "lastname"), 'label', array("attr" => array("class" => "control-label"), "label" => "label_profile_lastname"));
        echo "
            <div class=\"controls\">";
        // line 36
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute($this->getContext($context, "form"), "lastname"), 'widget');
        echo "</div>
        </div>
        <div class=\"control-group\">
            ";
        // line 39
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute($this->getContext($context, "form"), "website"), 'label', array("attr" => array("class" => "control-label"), "label" => "label_profile_website"));
        echo "
            <div class=\"controls\">";
        // line 40
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute($this->getContext($context, "form"), "website"), 'widget');
        echo "</div>
        </div>
        <div class=\"control-group\">
            ";
        // line 43
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute($this->getContext($context, "form"), "dateOfBirth"), 'label', array("attr" => array("class" => "control-label"), "label" => "label_profile_dob"));
        echo "
            <div class=\"controls\">";
        // line 44
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute($this->getContext($context, "form"), "dateOfBirth"), 'widget', array("attr" => array("class" => "span1")));
        echo "</div>
        </div>
        <div class=\"control-group\">
            ";
        // line 47
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute($this->getContext($context, "form"), "biography"), 'label', array("attr" => array("class" => "control-label"), "label" => "label_profile_biography"));
        echo "
            <div class=\"controls\">";
        // line 48
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute($this->getContext($context, "form"), "biography"), 'widget');
        echo "</div>
        </div>
        <div class=\"control-group\">
            ";
        // line 51
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute($this->getContext($context, "form"), "locale"), 'label', array("attr" => array("class" => "control-label"), "label" => "label_profile_locale"));
        echo "
            <div class=\"controls\">";
        // line 52
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute($this->getContext($context, "form"), "locale"), 'widget');
        echo "</div>
        </div>
        <div class=\"control-group\">
            ";
        // line 55
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute($this->getContext($context, "form"), "timezone"), 'label', array("attr" => array("class" => "control-label"), "label" => "label_profile_timezone"));
        echo "
            <div class=\"controls\">";
        // line 56
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute($this->getContext($context, "form"), "timezone"), 'widget');
        echo "</div>
        </div>
        <div class=\"control-group\">
            ";
        // line 59
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute($this->getContext($context, "form"), "phone"), 'label', array("attr" => array("class" => "control-label"), "label" => "label_profile_phone"));
        echo "
            <div class=\"controls\">";
        // line 60
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute($this->getContext($context, "form"), "phone"), 'widget');
        echo "</div>
        </div>

        ";
        // line 63
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getContext($context, "form"), 'rest');
        echo "
        <input type=\"submit\" name=\"submit\" value=\"";
        // line 64
        echo twig_escape_filter($this->env, $this->env->getExtension('translator')->trans("sonata_user_submit", array(), "SonataUserBundle"), "html", null, true);
        echo "\" />
    </form>
</div>
";
    }

    // line 20
    public function block_field_row($context, array $blocks = array())
    {
        // line 21
        echo "
        ";
        // line 23
        echo "    ";
    }

    public function getTemplateName()
    {
        return "SonataUserBundle:Profile:edit_profile.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  153 => 67,  388 => 154,  346 => 136,  323 => 124,  319 => 123,  315 => 122,  212 => 75,  202 => 72,  643 => 389,  634 => 383,  630 => 381,  628 => 377,  618 => 374,  597 => 361,  593 => 360,  587 => 357,  567 => 346,  559 => 341,  535 => 326,  504 => 306,  492 => 299,  463 => 284,  458 => 282,  446 => 275,  428 => 265,  420 => 260,  390 => 155,  354 => 219,  281 => 167,  222 => 138,  217 => 136,  297 => 177,  291 => 135,  256 => 122,  251 => 120,  227 => 105,  221 => 78,  215 => 76,  210 => 97,  207 => 129,  194 => 89,  188 => 86,  161 => 70,  129 => 55,  333 => 85,  330 => 84,  325 => 82,  299 => 75,  290 => 72,  288 => 109,  270 => 100,  267 => 99,  261 => 96,  244 => 56,  239 => 86,  231 => 83,  213 => 23,  170 => 89,  159 => 54,  150 => 93,  124 => 44,  113 => 40,  12 => 45,  631 => 203,  625 => 201,  623 => 200,  617 => 196,  608 => 370,  604 => 368,  595 => 190,  591 => 189,  579 => 185,  571 => 183,  568 => 182,  565 => 181,  549 => 334,  545 => 97,  534 => 92,  529 => 321,  526 => 90,  514 => 79,  511 => 310,  505 => 90,  501 => 88,  496 => 301,  493 => 76,  489 => 171,  459 => 159,  442 => 155,  434 => 153,  431 => 152,  415 => 147,  410 => 253,  394 => 242,  332 => 117,  301 => 76,  284 => 110,  265 => 125,  262 => 157,  257 => 74,  249 => 71,  185 => 83,  174 => 51,  613 => 372,  610 => 204,  605 => 199,  598 => 191,  592 => 192,  588 => 190,  586 => 187,  583 => 356,  577 => 353,  564 => 181,  561 => 180,  553 => 177,  550 => 176,  544 => 174,  542 => 330,  539 => 172,  536 => 171,  531 => 324,  527 => 320,  507 => 102,  502 => 122,  499 => 78,  494 => 120,  491 => 119,  485 => 292,  481 => 109,  478 => 108,  470 => 105,  464 => 104,  449 => 100,  441 => 97,  436 => 96,  433 => 95,  424 => 93,  417 => 87,  411 => 86,  406 => 252,  403 => 84,  397 => 48,  389 => 137,  370 => 229,  367 => 39,  361 => 127,  357 => 126,  351 => 137,  347 => 124,  342 => 134,  339 => 133,  336 => 131,  327 => 204,  320 => 171,  316 => 80,  313 => 79,  307 => 166,  304 => 165,  296 => 74,  253 => 147,  248 => 89,  242 => 142,  234 => 140,  232 => 139,  226 => 81,  205 => 95,  198 => 132,  179 => 63,  155 => 68,  127 => 54,  335 => 94,  326 => 90,  306 => 77,  303 => 139,  279 => 68,  276 => 165,  273 => 66,  271 => 163,  259 => 123,  255 => 60,  245 => 66,  240 => 64,  218 => 57,  211 => 53,  206 => 21,  190 => 69,  181 => 115,  172 => 76,  167 => 73,  134 => 42,  65 => 39,  63 => 20,  686 => 206,  680 => 203,  677 => 202,  675 => 201,  669 => 198,  659 => 197,  654 => 195,  642 => 193,  639 => 192,  636 => 205,  627 => 185,  624 => 184,  607 => 182,  590 => 181,  585 => 179,  581 => 178,  578 => 177,  575 => 185,  572 => 184,  566 => 182,  562 => 169,  560 => 100,  555 => 178,  538 => 93,  521 => 317,  517 => 163,  512 => 124,  509 => 161,  506 => 160,  503 => 159,  500 => 158,  498 => 157,  495 => 156,  486 => 151,  482 => 166,  477 => 147,  475 => 146,  472 => 287,  468 => 286,  462 => 123,  456 => 158,  450 => 157,  443 => 140,  432 => 136,  426 => 151,  421 => 131,  416 => 129,  405 => 127,  400 => 248,  391 => 138,  386 => 115,  380 => 112,  377 => 148,  375 => 231,  371 => 145,  366 => 107,  359 => 106,  356 => 139,  353 => 138,  343 => 122,  340 => 97,  331 => 92,  329 => 127,  324 => 113,  321 => 81,  318 => 90,  310 => 184,  302 => 116,  289 => 156,  286 => 85,  282 => 69,  277 => 104,  274 => 77,  272 => 76,  269 => 75,  263 => 71,  254 => 92,  250 => 58,  243 => 87,  238 => 64,  236 => 50,  233 => 84,  228 => 82,  223 => 64,  200 => 92,  197 => 60,  191 => 119,  184 => 114,  178 => 83,  175 => 16,  146 => 64,  118 => 52,  110 => 51,  104 => 49,  90 => 35,  81 => 172,  59 => 18,  23 => 18,  76 => 36,  58 => 23,  53 => 21,  20 => 11,  152 => 67,  148 => 63,  137 => 88,  102 => 47,  100 => 22,  97 => 37,  77 => 58,  34 => 16,  480 => 290,  474 => 164,  469 => 162,  461 => 160,  457 => 153,  453 => 101,  444 => 98,  440 => 272,  437 => 138,  435 => 270,  430 => 94,  427 => 143,  423 => 149,  413 => 146,  409 => 132,  407 => 131,  402 => 143,  398 => 243,  393 => 156,  387 => 122,  384 => 237,  381 => 150,  379 => 236,  374 => 116,  368 => 112,  365 => 142,  362 => 110,  360 => 140,  355 => 106,  341 => 121,  337 => 96,  322 => 201,  314 => 99,  312 => 89,  309 => 97,  305 => 182,  298 => 84,  294 => 136,  285 => 168,  283 => 107,  278 => 86,  268 => 104,  264 => 98,  258 => 81,  252 => 59,  247 => 117,  241 => 149,  235 => 74,  229 => 138,  224 => 71,  220 => 63,  214 => 69,  208 => 73,  169 => 50,  143 => 45,  140 => 56,  132 => 59,  128 => 55,  119 => 42,  107 => 44,  71 => 30,  38 => 25,  177 => 78,  165 => 64,  160 => 23,  135 => 58,  126 => 56,  114 => 23,  84 => 35,  70 => 33,  67 => 27,  61 => 25,  94 => 21,  89 => 40,  85 => 32,  75 => 35,  68 => 49,  56 => 28,  196 => 71,  183 => 84,  171 => 61,  166 => 73,  163 => 49,  158 => 67,  156 => 68,  151 => 66,  142 => 63,  138 => 61,  136 => 60,  121 => 43,  117 => 36,  105 => 26,  91 => 38,  62 => 31,  49 => 12,  87 => 59,  21 => 12,  31 => 16,  25 => 14,  26 => 2,  28 => 14,  24 => 14,  19 => 11,  93 => 43,  88 => 31,  78 => 29,  46 => 9,  44 => 24,  27 => 14,  79 => 31,  72 => 35,  69 => 30,  47 => 25,  40 => 19,  37 => 18,  22 => 12,  246 => 70,  157 => 21,  145 => 63,  139 => 60,  131 => 55,  123 => 54,  120 => 53,  115 => 75,  111 => 39,  108 => 47,  101 => 38,  98 => 47,  96 => 44,  83 => 15,  74 => 31,  66 => 32,  55 => 15,  52 => 27,  50 => 30,  43 => 8,  41 => 17,  35 => 17,  32 => 16,  29 => 3,  209 => 82,  203 => 128,  199 => 20,  193 => 129,  189 => 71,  187 => 68,  182 => 66,  176 => 62,  173 => 76,  168 => 66,  164 => 59,  162 => 62,  154 => 20,  149 => 53,  147 => 64,  144 => 92,  141 => 61,  133 => 47,  130 => 56,  125 => 55,  122 => 55,  116 => 52,  112 => 51,  109 => 47,  106 => 48,  103 => 45,  99 => 44,  95 => 64,  92 => 43,  86 => 40,  82 => 39,  80 => 36,  73 => 32,  64 => 26,  60 => 25,  57 => 34,  54 => 25,  51 => 38,  48 => 21,  45 => 10,  42 => 20,  39 => 19,  36 => 16,  33 => 15,  30 => 15,);
    }
}
