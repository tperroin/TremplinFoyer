<?php

/* ::baseIndex.html.twig */
class __TwigTemplate_0220359b591a56091a8c3ac18d3c9648 extends Twig_Template
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
            'contact' => array($this, 'block_contact'),
            'partenaires' => array($this, 'block_partenaires'),
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
            
        <div class=\"large-9 columns\">
            ";
        // line 73
        $this->displayBlock('corps', $context, $blocks);
        // line 74
        echo "        </div>
        <script>
            \$(\"#partenaire > div:gt(0)\").hide();
            
            setInterval(function() { 
              \$('#partenaire > div:first')
                .fadeOut(1000)
                .next('div')
                .fadeIn(1000)
                .end()
                .appendTo('#partenaire');
            },  3000);
            
            </script>
         
        <aside class=\"large-3 columns\" >
            
            <div class=\"blocBlanc\">
                <h3>Contact</h3>
                
                <div class=\"centrer\">
                    ";
        // line 95
        $this->displayBlock('contact', $context, $blocks);
        // line 99
        echo "                </div>
                
            </div>
            <div class=\"blocBlanc\">
                <h3>Partenaires</h3>
                
                <div id=\"partenaire\">
                    ";
        // line 106
        $this->displayBlock('partenaires', $context, $blocks);
        // line 107
        echo "                </div>
                
            </div>
        </aside>
        
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
        // line 125
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
            <div class=\"large-2 columns\">
                <a href=\"";
        // line 34
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("homepage"), "html", null, true);
        echo "\"><img src=\"";
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("styles/img/logo.jpg"), "html", null, true);
        echo "\" /></a>
            </div>
           
        ";
    }

    // line 41
    public function block_navbar($context, array $blocks = array())
    {
        echo " 
            <div class=\"large-7 columns\">
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

    // line 73
    public function block_corps($context, array $blocks = array())
    {
    }

    // line 95
    public function block_contact($context, array $blocks = array())
    {
        // line 96
        echo "                        <a href=\"https://www.facebook.com/foyer.desjeunesdoudon\"><img src=\"";
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("styles/img/facebook.png"), "html", null, true);
        echo "\"/></a>
                        <a href=\"mailto:valougb44@hotmail.fr\"><img src=\"";
        // line 97
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("styles/img/email.png"), "html", null, true);
        echo "\"/></a>
";
    }

    // line 106
    public function block_partenaires($context, array $blocks = array())
    {
    }

    public function getTemplateName()
    {
        return "::baseIndex.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  282 => 106,  276 => 97,  271 => 96,  268 => 95,  263 => 73,  245 => 55,  242 => 54,  232 => 47,  228 => 46,  224 => 45,  220 => 44,  213 => 41,  203 => 34,  196 => 31,  189 => 21,  185 => 20,  180 => 19,  177 => 18,  171 => 15,  167 => 14,  163 => 13,  158 => 12,  155 => 11,  149 => 9,  136 => 125,  116 => 107,  114 => 106,  105 => 99,  103 => 95,  80 => 74,  78 => 73,  73 => 70,  71 => 54,  68 => 53,  66 => 41,  61 => 38,  59 => 31,  50 => 24,  48 => 18,  45 => 17,  43 => 11,  38 => 9,  28 => 1,);
    }
}
