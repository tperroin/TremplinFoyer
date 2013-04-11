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

        <!-- Set the viewport width to device width for mobile -->
        <meta name=\"viewport\" content=\"width=device-width\" />

        <title>";
        // line 10
        $this->displayBlock('title', $context, $blocks);
        echo "</title>

        ";
        // line 12
        $this->displayBlock('stylesheets', $context, $blocks);
        // line 18
        echo "            
        ";
        // line 19
        $this->displayBlock('javascripts', $context, $blocks);
        // line 23
        echo "            


    </head>

    <body>

        <!-- Header and Nav -->
        
        
        
            
        ";
        // line 35
        $this->displayBlock('logo', $context, $blocks);
        // line 42
        echo "


        ";
        // line 45
        $this->displayBlock('navbar', $context, $blocks);
        // line 57
        echo "        
        ";
        // line 58
        $this->displayBlock('annonce', $context, $blocks);
        // line 74
        echo "        
        ";
        // line 75
        $this->displayBlock('corps', $context, $blocks);
        // line 76
        echo "        
         ";
        // line 77
        $this->displayBlock('partenaires', $context, $blocks);
        // line 114
        echo "
       
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
        // line 127
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("sonata_admin_dashboard"), "html", null, true);
        echo "\">Administration</a></li>
                    </ul>
                </div>
            </div>
        </div> 
    </footer>

    
</html>";
    }

    // line 10
    public function block_title($context, array $blocks = array())
    {
        echo "Tremplin musicale : Ô tour du son";
    }

    // line 12
    public function block_stylesheets($context, array $blocks = array())
    {
        // line 13
        echo "            <link rel=\"stylesheet\" href=\"";
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("styles/css/foundation.css"), "html", null, true);
        echo "\" type=\"text/css\" />
            <link rel=\"stylesheet\" href=\"";
        // line 14
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("styles/css/normalize.css"), "html", null, true);
        echo "\" type=\"text/css\" />
            <link rel=\"stylesheet\" href=\"";
        // line 15
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("styles/css/app.css"), "html", null, true);
        echo "\" type=\"text/css\" />
            <link rel=\"stylesheet\" href=\"";
        // line 16
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("styles/css/foundation.min.css"), "html", null, true);
        echo "\" type=\"text/css\" />
        ";
    }

    // line 19
    public function block_javascripts($context, array $blocks = array())
    {
        // line 20
        echo "            

        ";
    }

    // line 35
    public function block_logo($context, array $blocks = array())
    {
        echo " 
        <div class=\"row head\">
            <div class=\"large-3 columns\">
                <a href=\"";
        // line 38
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("homepage"), "html", null, true);
        echo "\"><img src=\"";
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("styles/img/logo.jpg"), "html", null, true);
        echo "\" alt=\"Symfony!\" /></a>
            </div>
           
        ";
    }

    // line 45
    public function block_navbar($context, array $blocks = array())
    {
        echo " 
            <div class=\"large-9 columns\">
                <ul class=\"button-group right\">
                    <li><a href=\"";
        // line 48
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("association"), "html", null, true);
        echo "\" class=\"button\">L'association</a></li>
                    <li><a href=\"";
        // line 49
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("homepage"), "html", null, true);
        echo "\" class=\"button\">Le tremplin</a></li>
                    <li><a href=\"";
        // line 50
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("homepage"), "html", null, true);
        echo "\" class=\"button\">Les groupes</a></li>
                    <li><a href=\"";
        // line 51
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("sonata_news_home"), "html", null, true);
        echo "\" class=\"button\">Blog</a></li>
                    
                </ul>
            </div>
        </div>
        ";
    }

    // line 58
    public function block_annonce($context, array $blocks = array())
    {
        // line 59
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

    // line 75
    public function block_corps($context, array $blocks = array())
    {
    }

    // line 77
    public function block_partenaires($context, array $blocks = array())
    {
        // line 78
        echo "    <div class=\"row\">
 
        <div class=\"row\">
          <h3>Partenaires</h3>
        </div>
 
          <div class=\"large-3 small-6 columns\">
            <img src=\"";
        // line 85
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("styles/img/partenaireMauges.jpg"), "html", null, true);
        echo "\">
            <div class=\"panel\">
              <p>Partenaire 1</p>
            </div>
          </div>
 
          <div class=\"large-3 small-6 columns\">
            <img src=\"";
        // line 92
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("styles/img/partenaireOudon.png"), "html", null, true);
        echo "\">
            <div class=\"panel\">
              <p>Partenaire 2</p>
            </div>
          </div>
 
          <div class=\"large-3 small-6 columns\">
            <img src=\"http://placehold.it/500x500&text=Thumbnail\">
            <div class=\"panel\">
              <p>Partenaire 3</p>
            </div>
          </div>
 
          <div class=\"large-3 small-6 columns\">
            <img src=\"http://placehold.it/500x500&text=Thumbnail\">
            <div class=\"panel\">
              <p>Partenaire 4</p>
            </div>
          </div>
 
        </div>
";
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
        return array (  247 => 92,  237 => 85,  228 => 78,  225 => 77,  220 => 75,  202 => 59,  199 => 58,  189 => 51,  185 => 50,  181 => 49,  177 => 48,  170 => 45,  160 => 38,  153 => 35,  147 => 20,  144 => 19,  138 => 16,  134 => 15,  130 => 14,  125 => 13,  122 => 12,  116 => 10,  103 => 127,  88 => 114,  86 => 77,  83 => 76,  81 => 75,  78 => 74,  76 => 58,  73 => 57,  71 => 45,  66 => 42,  64 => 35,  50 => 23,  48 => 19,  45 => 18,  43 => 12,  38 => 10,  27 => 1,);
    }
}
