<?php

/* TperroinTremplinFoyerBundle:Default:index.html.twig */
class __TwigTemplate_2a46e265a3075eea4e726fbef558d9ea extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = $this->env->loadTemplate("::baseIndex.html.twig");

        $this->blocks = array(
            'corps' => array($this, 'block_corps'),
            'partenaires' => array($this, 'block_partenaires'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "::baseIndex.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_corps($context, array $blocks = array())
    {
        // line 4
        echo "

  <div class=\"row blocBlanc\">
    <div class=\"large-4 columns columns\">
      <img src=\"";
        // line 8
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("styles/img/presentation.jpg"), "html", null, true);
        echo "\" />
    </div>
    <div class=\"large-8 columns\">
      <h4>Présentation.</h4>
      <div class=\"row\">
        <p>L'association, Ô Tour du Globe, est présente depuis une dizaine d'années dans la vie culturelle de la commune d'Oudon via sa scène lors la fête de la musique.</p>
        <p>Elle parvient chaque année à se faire connaître un peu plus en attirant des groupes amateurs commençant à se forger un nom et des groupes semi-professionnels. L'association va aujourd'hui plus loin en organisant son propre évènement : le Tremplin Ô Tour du Son.</p>
        <p>L'association souhaite donner à 3 groupes, une possibilité d'exposer toute  l'étendue de leur talent lors de cette soirée.</p>
        <p>À la clé une récompense pour le groupe ayant recueillit le plus de suffrages, à savoir : la possibilité de jouer sur notre scène durant la prochaine fête de la musique. Ceci ayant pour but de mettre doublement en avant le talent du groupe vainqueur.</p>
        <p>À ces 3 groupes viennent s'ajouter deux groupes hors concours.</p>
        <p>Nous vous attendons donc nombreux pour venir découvrir et soutenir les différents groupes de la soirée.</p>
      </div>
    </div>
  </div>


";
    }

    // line 26
    public function block_partenaires($context, array $blocks = array())
    {
        // line 27
        echo "
    ";
        // line 28
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable($this->getContext($context, "entities"));
        foreach ($context['_seq'] as $context["_key"] => $context["entity"]) {
            // line 29
            echo "
        
            <div>
                <a href=\"";
            // line 32
            echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "entity"), "site"), "html", null, true);
            echo "\" target=\"_blank\">
                    ";
            // line 33
            echo $this->env->getExtension('sonata_media')->media($this->getAttribute($this->getContext($context, "entity"), "image"), "big", array());
            // line 34
            echo "                <div class=\"fondBlanc\">
                    ";
            // line 35
            echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "entity"), "nom"), "html", null, true);
            echo "
                </div></a>
            </div>
        

    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['entity'], $context['_parent'], $context['loop']);
        $context = array_merge($_parent, array_intersect_key($context, $_parent));
    }

    public function getTemplateName()
    {
        return "TperroinTremplinFoyerBundle:Default:index.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  83 => 35,  80 => 34,  78 => 33,  74 => 32,  69 => 29,  65 => 28,  62 => 27,  59 => 26,  38 => 8,  32 => 4,  29 => 3,);
    }
}
