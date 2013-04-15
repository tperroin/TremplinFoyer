<?php

/* ::base.html.twig */
class __TwigTemplate_c3352d25fea9b151e90ef46bf62369f8 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
            'title' => array($this, 'block_title'),
            'stylesheets' => array($this, 'block_stylesheets'),
            'javascripts' => array($this, 'block_javascripts'),
            'logo' => array($this, 'block_logo'),
            'navbar' => array($this, 'block_navbar'),
            'annonce' => array($this, 'block_annonce'),
            'corps' => array($this, 'block_corps'),
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<!DOCTYPE html>
<html>
    <head>

        <meta charset=\"utf-8\" />

        <meta name=\"viewport\" content=\"width=device-width\" />

        <title>";
        // line 9
        $this->displayBlock('title', $context, $blocks);
        echo "</title>

        ";
        // line 11
        $this->displayBlock('stylesheets', $context, $blocks);
        // line 17
        echo "            
        ";
        // line 18
        $this->displayBlock('javascripts', $context, $blocks);
        // line 24
        echo "            


    </head>

    <body>

        ";
        // line 31
        $this->displayBlock('logo', $context, $blocks);
        // line 38
        echo "


        ";
        // line 41
        $this->displayBlock('navbar', $context, $blocks);
        // line 53
        echo "        
        ";
        // line 54
        $this->displayBlock('annonce', $context, $blocks);
        // line 70
        echo "        <div class=\"row\">

            ";
        // line 72
        $this->displayBlock('corps', $context, $blocks);
        // line 73
        echo "

         

        
        </div>
        
    </body>

    <footer class=\"row\">
        <div class=\"large-12 columns\">
            <hr />
            <div class=\"row\">
                <div class=\"large-6 columns\">
                    <p>&copy; Copyright Ô tour du globe</p>
                </div>
                <div class=\"large-6 columns\">
                    <ul class=\"link-list right\">
                        <li><a href=\"";
        // line 91
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("sonata_admin_dashboard"), "html", null, true);
        echo "\">Administration</a></li>
                    </ul>
                </div>
            </div>
        </div> 
    </footer>

    
</html>";
    }

    // line 9
    public function block_title($context, array $blocks = array())
    {
        echo "Ô tour du globe";
    }

    // line 11
    public function block_stylesheets($context, array $blocks = array())
    {
        // line 12
        echo "            <link rel=\"stylesheet\" href=\"";
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("styles/css/foundation.css"), "html", null, true);
        echo "\" type=\"text/css\" />
            <link rel=\"stylesheet\" href=\"";
        // line 13
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("styles/css/normalize.css"), "html", null, true);
        echo "\" type=\"text/css\" />
            <link rel=\"stylesheet\" href=\"";
        // line 14
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("styles/css/app.css"), "html", null, true);
        echo "\" type=\"text/css\" />
            <link rel=\"stylesheet\" href=\"";
        // line 15
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("styles/css/foundation.min.css"), "html", null, true);
        echo "\" type=\"text/css\" />
        ";
    }

    // line 18
    public function block_javascripts($context, array $blocks = array())
    {
        // line 19
        echo "            <script src=\"";
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("styles/js/vendor/jquery.js"), "html", null, true);
        echo "\" type=\"text/javascript\"></script>
            <script src=\"";
        // line 20
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("styles/js/vendor/zepto.js"), "html", null, true);
        echo "\" type=\"text/javascript\"></script>
            <script src=\"";
        // line 21
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("styles/js/foundation/foundation.js"), "html", null, true);
        echo "\" type=\"text/javascript\"></script>

        ";
    }

    // line 31
    public function block_logo($context, array $blocks = array())
    {
        echo " 
        <div class=\"row head\">
            <div class=\"large-3 columns\">
                <a href=\"";
        // line 34
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("homepage"), "html", null, true);
        echo "\"><img src=\"";
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("styles/img/logo.jpg"), "html", null, true);
        echo "\" alt=\"Symfony!\" /></a>
            </div>
           
        ";
    }

    // line 41
    public function block_navbar($context, array $blocks = array())
    {
        echo " 
            <div class=\"large-9 columns\">
                <ul class=\"button-group right\">
                    <li><a href=\"";
        // line 44
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("association"), "html", null, true);
        echo "\" class=\"button\">L'association</a></li>
                    <li><a href=\"";
        // line 45
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("tremplin"), "html", null, true);
        echo "\" class=\"button\">Le tremplin</a></li>
                    <li><a href=\"";
        // line 46
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("groupe"), "html", null, true);
        echo "\" class=\"button\">Les groupes</a></li>
                    <li><a href=\"";
        // line 47
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("sonata_news_home"), "html", null, true);
        echo "\" class=\"button\">Blog</a></li>
                    
                </ul>
            </div>
        </div>
        ";
    }

    // line 54
    public function block_annonce($context, array $blocks = array())
    {
        // line 55
        echo "            <div class=\"row\">
                <div class=\"large-4\"
                    <hr />
                </div>
            </div>
            <header>
                <div class=\"row\">
                    <div class=\"twelve columns\">
                        <h1>Ô tour du son !</h1>
                        <h2>Edition #3 le 14 Septembre 2013</h2>
                        <h4>Au local du foyer des jeunes à Oudon</h4>
                    </div>
                </div>
            </header>
        ";
    }

    // line 72
    public function block_corps($context, array $blocks = array())
    {
    }

    public function getTemplateName()
    {
        return "::base.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  224 => 72,  206 => 55,  203 => 54,  193 => 47,  189 => 46,  185 => 45,  181 => 44,  174 => 41,  164 => 34,  157 => 31,  150 => 21,  146 => 20,  141 => 19,  138 => 18,  132 => 15,  128 => 14,  124 => 13,  119 => 12,  116 => 11,  110 => 9,  97 => 91,  77 => 73,  75 => 72,  71 => 70,  69 => 54,  66 => 53,  64 => 41,  59 => 38,  57 => 31,  48 => 24,  46 => 18,  41 => 11,  36 => 9,  26 => 1,  80 => 31,  67 => 24,  60 => 19,  58 => 18,  51 => 16,  43 => 17,  39 => 9,  31 => 3,  28 => 2,);
    }
}
