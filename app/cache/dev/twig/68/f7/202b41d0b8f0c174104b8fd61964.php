<?php

/* SonataMediaBundle:MediaAdmin:view.html.twig */
class __TwigTemplate_68f7202b41d0b8f0c174104b8fd61964 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = $this->env->loadTemplate("SonataAdminBundle:CRUD:action.html.twig");

        $this->blocks = array(
            'title' => array($this, 'block_title'),
            'content' => array($this, 'block_content'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "SonataAdminBundle:CRUD:action.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 14
    public function block_title($context, array $blocks = array())
    {
        echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "media"), "name"), "html", null, true);
    }

    // line 16
    public function block_content($context, array $blocks = array())
    {
        // line 17
        echo "    <h2>";
        echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "media"), "name"), "html", null, true);
        echo " (";
        echo twig_escape_filter($this->env, $this->getContext($context, "format"), "html", null, true);
        echo ")</h2>

    <h3>";
        // line 19
        echo twig_escape_filter($this->env, $this->env->getExtension('translator')->trans("title.media_preview", array(), "SonataMediaBundle"), "html", null, true);
        echo "</h3>
    <div>
        ";
        // line 21
        echo $this->env->getExtension('sonata_media')->media($this->getContext($context, "media"), $this->getContext($context, "format"), array());
        // line 22
        echo "    </div>

    <h3>";
        // line 24
        echo twig_escape_filter($this->env, $this->env->getExtension('translator')->trans("title.informations", array(), "SonataMediaBundle"), "html", null, true);
        echo "</h3>

    <table>
        ";
        // line 27
        if (($this->getContext($context, "pixlr") && $this->getAttribute($this->getContext($context, "pixlr"), "isEditable", array(0 => $this->getContext($context, "media")), "method"))) {
            // line 28
            echo "            <tr>
                <td></td>
                <td><a class=\"btn\" href=\"";
            // line 30
            echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("sonata_media_pixlr_open_editor", array("id" => $this->env->getExtension('sonata_admin')->getUrlsafeIdentifier($this->getContext($context, "media")))), "html", null, true);
            echo "\">";
            echo twig_escape_filter($this->env, $this->env->getExtension('translator')->trans("label.edit_with_pixlr", array(), "SonataMediaBundle"), "html", null, true);
            echo "</a></td>
            </tr>
        ";
        }
        // line 33
        echo "        <tr>
            <td>";
        // line 34
        echo twig_escape_filter($this->env, $this->env->getExtension('translator')->trans("label.size", array(), "SonataMediaBundle"), "html", null, true);
        echo "</td>
            <td>";
        // line 35
        echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "media"), "size"), "html", null, true);
        echo "</td>
        <tr>
        <tr>
            <td>";
        // line 38
        echo twig_escape_filter($this->env, $this->env->getExtension('translator')->trans("label.width", array(), "SonataMediaBundle"), "html", null, true);
        echo "</td>
            <td>";
        // line 39
        echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "media"), "width"), "html", null, true);
        echo "</td>
        <tr>
        <tr>
            <td>";
        // line 42
        echo twig_escape_filter($this->env, $this->env->getExtension('translator')->trans("label.height", array(), "SonataMediaBundle"), "html", null, true);
        echo "</td>
            <td>";
        // line 43
        echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "media"), "height"), "html", null, true);
        echo "</td>
        <tr>
        <tr>
            <td>";
        // line 46
        echo twig_escape_filter($this->env, $this->env->getExtension('translator')->trans("label.content_type", array(), "SonataMediaBundle"), "html", null, true);
        echo "</td>
            <td>";
        // line 47
        echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "media"), "contenttype"), "html", null, true);
        echo "</td>
        <tr>
        <tr>
            <td>";
        // line 50
        echo twig_escape_filter($this->env, $this->env->getExtension('translator')->trans("label.copyright", array(), "SonataMediaBundle"), "html", null, true);
        echo "</td>
            <td>";
        // line 51
        echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "media"), "copyright"), "html", null, true);
        echo "</td>
        <tr>
        <tr>
            <td>";
        // line 54
        echo twig_escape_filter($this->env, $this->env->getExtension('translator')->trans("label.author_name", array(), "SonataMediaBundle"), "html", null, true);
        echo "</td>
            <td>";
        // line 55
        echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "media"), "authorname"), "html", null, true);
        echo "</td>
        <tr>
        <tr>
            <td>";
        // line 58
        echo twig_escape_filter($this->env, $this->env->getExtension('translator')->trans("label.cdn", array(), "SonataMediaBundle"), "html", null, true);
        echo "</td>
            <td>
                ";
        // line 60
        if ($this->getAttribute($this->getContext($context, "media"), "cdnisflushable")) {
            // line 61
            echo "                    ";
            echo twig_escape_filter($this->env, $this->env->getExtension('translator')->trans("label.to_be_flushed", array(), "SonataMediaBundle"), "html", null, true);
            echo "
                ";
        } else {
            // line 63
            echo "                    ";
            echo twig_escape_filter($this->env, $this->env->getExtension('translator')->trans("label.flushed_at", array(), "SonataMediaBundle"), "html", null, true);
            echo "
                    ";
            // line 64
            echo twig_escape_filter($this->env, twig_date_format_filter($this->env, $this->getAttribute($this->getContext($context, "media"), "cdnflushat")), "html", null, true);
            echo "
                 ";
        }
        // line 66
        echo "            </td>
        <tr>
        <tr>
            <td>";
        // line 69
        echo twig_escape_filter($this->env, $this->env->getExtension('translator')->trans("label.protected_download_url", array(), "SonataMediaBundle"), "html", null, true);
        echo "</td>
            <td>
                <input type=\"text\" value=\"";
        // line 71
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("sonata_media_download", array("id" => $this->env->getExtension('sonata_admin')->getUrlsafeIdentifier($this->getContext($context, "media")))), "html", null, true);
        echo "\" style=\"width:500px\"/>
                <a href=\"";
        // line 72
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("sonata_media_download", array("id" => $this->env->getExtension('sonata_admin')->getUrlsafeIdentifier($this->getContext($context, "media")))), "html", null, true);
        echo "\">";
        echo twig_escape_filter($this->env, $this->env->getExtension('translator')->trans("link.test_protected_url", array(), "SonataMediaBundle"), "html", null, true);
        echo "</a>
                <br />
                <span class=\"label warning\">";
        // line 74
        echo twig_escape_filter($this->env, $this->env->getExtension('translator')->trans("label.protected_download_url_notice", array(), "SonataMediaBundle"), "html", null, true);
        echo "</span> ";
        echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "security"), "description"), "html", null, true);
        echo "
            </td>
        <tr>

        <tr>
            <td>";
        // line 79
        echo twig_escape_filter($this->env, $this->env->getExtension('translator')->trans("label.public_url", array(), "SonataMediaBundle"), "html", null, true);
        echo "</td>
            <td><input type=\"text\" value=\"";
        // line 80
        echo $this->env->getExtension('sonata_media')->path($this->getContext($context, "media"), $this->getContext($context, "format"));
        echo "\" style=\"width:500px\"/></td>
        <tr>
    </table>

    <h3>";
        // line 84
        echo twig_escape_filter($this->env, $this->env->getExtension('translator')->trans("title.formats", array(), "SonataMediaBundle"), "html", null, true);
        echo "</h3>
    <table>
        <tr>
            <td>
                <a href=\"";
        // line 88
        echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "admin"), "generateUrl", array(0 => "view", 1 => array("id" => $this->env->getExtension('sonata_admin')->getUrlsafeIdentifier($this->getContext($context, "media")), "format" => "reference")), "method"), "html", null, true);
        echo "\">reference</a>
            </td>
            <td>
                <input type=\"text\" value=\"";
        // line 91
        echo $this->env->getExtension('sonata_media')->path($this->getContext($context, "media"), "reference");
        echo "\"  style=\"width:500px\" />
            </td>
        </tr>

        ";
        // line 95
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable($this->getContext($context, "formats"));
        foreach ($context['_seq'] as $context["name"] => $context["format"]) {
            // line 96
            echo "            <tr>
                <td>
                    <a href=\"";
            // line 98
            echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "admin"), "generateUrl", array(0 => "view", 1 => array("id" => $this->env->getExtension('sonata_admin')->getUrlsafeIdentifier($this->getContext($context, "media")), "format" => $this->getContext($context, "name"))), "method"), "html", null, true);
            echo "\">";
            echo twig_escape_filter($this->env, $this->getContext($context, "name"), "html", null, true);
            echo "</a>
                </td>
                <td>
                    <input type=\"text\" value=\"";
            // line 101
            echo $this->env->getExtension('sonata_media')->path($this->getContext($context, "media"), $this->getContext($context, "name"));
            echo "\"  style=\"width:500px\"/>
                </td>
            </tr>
        ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['name'], $context['format'], $context['_parent'], $context['loop']);
        $context = array_merge($_parent, array_intersect_key($context, $_parent));
        // line 105
        echo "    </table>
";
    }

    public function getTemplateName()
    {
        return "SonataMediaBundle:MediaAdmin:view.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  229 => 96,  244 => 84,  192 => 66,  224 => 72,  388 => 154,  381 => 150,  365 => 142,  351 => 137,  346 => 136,  339 => 133,  323 => 124,  319 => 123,  315 => 122,  288 => 109,  270 => 100,  261 => 96,  239 => 82,  231 => 79,  215 => 76,  630 => 381,  628 => 377,  613 => 372,  608 => 370,  597 => 361,  593 => 360,  587 => 357,  583 => 356,  577 => 353,  542 => 330,  535 => 326,  531 => 324,  527 => 320,  463 => 284,  458 => 282,  446 => 275,  440 => 272,  428 => 265,  420 => 260,  398 => 243,  390 => 155,  342 => 134,  248 => 89,  183 => 83,  158 => 64,  295 => 135,  292 => 134,  281 => 167,  275 => 127,  237 => 110,  221 => 78,  202 => 72,  150 => 21,  160 => 23,  129 => 45,  264 => 98,  260 => 71,  226 => 81,  220 => 59,  199 => 52,  188 => 48,  177 => 72,  161 => 56,  156 => 55,  148 => 65,  124 => 51,  113 => 40,  99 => 23,  12 => 45,  638 => 211,  633 => 209,  625 => 206,  619 => 202,  606 => 198,  602 => 197,  591 => 195,  586 => 193,  579 => 191,  571 => 189,  568 => 188,  565 => 187,  545 => 103,  534 => 98,  529 => 321,  526 => 96,  514 => 85,  511 => 310,  507 => 108,  505 => 96,  499 => 84,  496 => 301,  493 => 82,  489 => 177,  469 => 168,  459 => 165,  442 => 161,  413 => 152,  410 => 253,  394 => 242,  389 => 143,  379 => 236,  370 => 229,  357 => 132,  347 => 130,  341 => 127,  301 => 137,  265 => 109,  262 => 157,  249 => 118,  217 => 136,  214 => 65,  210 => 64,  185 => 64,  182 => 82,  174 => 41,  117 => 39,  140 => 58,  97 => 91,  679 => 218,  676 => 217,  671 => 210,  665 => 207,  656 => 203,  651 => 201,  645 => 199,  643 => 389,  640 => 197,  634 => 383,  632 => 194,  629 => 193,  623 => 191,  621 => 190,  618 => 374,  612 => 187,  610 => 199,  604 => 368,  599 => 157,  595 => 196,  589 => 153,  580 => 150,  570 => 148,  567 => 346,  559 => 341,  553 => 111,  549 => 334,  546 => 109,  532 => 105,  524 => 103,  504 => 306,  501 => 94,  492 => 299,  485 => 292,  474 => 170,  471 => 85,  465 => 49,  461 => 166,  457 => 47,  448 => 44,  431 => 158,  425 => 36,  415 => 153,  411 => 31,  403 => 28,  393 => 156,  384 => 237,  382 => 184,  369 => 179,  360 => 140,  355 => 172,  352 => 171,  338 => 170,  332 => 123,  305 => 182,  299 => 161,  291 => 157,  287 => 155,  285 => 168,  278 => 142,  266 => 138,  252 => 119,  241 => 101,  235 => 130,  227 => 105,  222 => 138,  193 => 47,  179 => 61,  166 => 91,  137 => 88,  86 => 31,  335 => 94,  326 => 90,  306 => 87,  303 => 163,  283 => 107,  279 => 115,  276 => 165,  273 => 112,  271 => 163,  268 => 110,  259 => 81,  255 => 70,  245 => 115,  218 => 91,  211 => 98,  206 => 55,  190 => 69,  187 => 68,  169 => 80,  167 => 58,  164 => 57,  134 => 55,  77 => 33,  65 => 28,  56 => 21,  53 => 22,  686 => 206,  680 => 203,  677 => 202,  675 => 201,  669 => 198,  659 => 204,  654 => 202,  642 => 193,  639 => 192,  636 => 191,  627 => 207,  624 => 184,  607 => 185,  590 => 181,  585 => 179,  581 => 178,  578 => 177,  575 => 149,  572 => 175,  566 => 171,  562 => 146,  560 => 106,  555 => 167,  538 => 99,  521 => 317,  517 => 101,  512 => 99,  509 => 98,  506 => 160,  503 => 159,  500 => 158,  498 => 95,  495 => 156,  486 => 151,  482 => 172,  480 => 290,  477 => 147,  475 => 146,  472 => 287,  462 => 123,  453 => 46,  450 => 163,  437 => 138,  435 => 270,  432 => 136,  423 => 155,  421 => 35,  416 => 129,  405 => 127,  402 => 149,  400 => 248,  391 => 144,  377 => 148,  375 => 231,  371 => 145,  366 => 178,  356 => 139,  353 => 138,  343 => 128,  337 => 96,  331 => 92,  329 => 127,  324 => 119,  318 => 90,  312 => 89,  310 => 184,  302 => 116,  298 => 84,  289 => 133,  286 => 85,  274 => 77,  272 => 75,  269 => 125,  254 => 92,  250 => 67,  247 => 66,  243 => 87,  238 => 65,  236 => 63,  233 => 98,  208 => 70,  203 => 54,  200 => 61,  197 => 68,  175 => 60,  173 => 71,  112 => 40,  110 => 46,  90 => 38,  87 => 41,  69 => 30,  49 => 12,  23 => 1,  82 => 39,  62 => 25,  40 => 18,  20 => 1,  479 => 87,  473 => 161,  468 => 286,  460 => 155,  456 => 164,  452 => 151,  443 => 42,  439 => 41,  436 => 147,  434 => 159,  429 => 144,  426 => 157,  422 => 142,  412 => 134,  408 => 132,  406 => 252,  401 => 130,  397 => 129,  392 => 126,  386 => 115,  383 => 121,  380 => 112,  378 => 183,  373 => 116,  367 => 112,  364 => 177,  361 => 133,  359 => 106,  354 => 219,  340 => 97,  336 => 131,  321 => 91,  313 => 99,  311 => 166,  308 => 97,  304 => 95,  297 => 177,  293 => 158,  284 => 116,  282 => 144,  277 => 104,  267 => 99,  263 => 123,  257 => 121,  251 => 105,  246 => 76,  240 => 66,  234 => 63,  228 => 82,  223 => 58,  219 => 67,  213 => 99,  207 => 129,  198 => 80,  181 => 44,  176 => 62,  170 => 75,  168 => 69,  146 => 49,  142 => 29,  128 => 14,  125 => 51,  107 => 44,  38 => 17,  144 => 54,  141 => 19,  135 => 50,  126 => 56,  109 => 44,  103 => 45,  67 => 27,  61 => 31,  47 => 20,  105 => 9,  93 => 43,  76 => 36,  72 => 28,  68 => 9,  225 => 95,  216 => 72,  212 => 88,  205 => 84,  201 => 69,  196 => 71,  194 => 79,  191 => 119,  189 => 46,  186 => 88,  180 => 72,  172 => 16,  159 => 61,  154 => 20,  147 => 61,  132 => 49,  127 => 44,  121 => 54,  118 => 53,  114 => 47,  104 => 43,  100 => 42,  78 => 20,  75 => 14,  71 => 70,  58 => 23,  34 => 16,  27 => 16,  91 => 33,  84 => 35,  44 => 19,  25 => 14,  28 => 14,  24 => 14,  19 => 2,  94 => 39,  88 => 31,  79 => 31,  59 => 24,  31 => 15,  26 => 1,  21 => 12,  70 => 22,  63 => 27,  46 => 19,  22 => 14,  163 => 66,  155 => 51,  152 => 50,  149 => 53,  145 => 60,  139 => 61,  131 => 22,  123 => 43,  120 => 50,  115 => 75,  106 => 48,  101 => 45,  96 => 35,  83 => 15,  80 => 34,  74 => 15,  66 => 53,  55 => 58,  52 => 27,  50 => 21,  43 => 8,  41 => 18,  37 => 18,  35 => 16,  32 => 15,  29 => 14,  184 => 74,  178 => 52,  171 => 61,  165 => 58,  162 => 57,  157 => 31,  153 => 63,  151 => 42,  143 => 45,  138 => 47,  136 => 60,  133 => 58,  130 => 54,  122 => 42,  119 => 12,  116 => 11,  111 => 39,  108 => 10,  102 => 37,  98 => 47,  95 => 64,  92 => 44,  89 => 32,  85 => 38,  81 => 15,  73 => 39,  64 => 26,  60 => 6,  57 => 24,  54 => 12,  51 => 21,  48 => 22,  45 => 21,  42 => 10,  39 => 19,  36 => 17,  33 => 6,  30 => 15,);
    }
}
