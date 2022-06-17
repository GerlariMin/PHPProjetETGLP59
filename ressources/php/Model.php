<?php

    /**
     * Classe Model
     * Elle permet d'assurer la connexion à une base de données et d'assurer les différentes requêtes communes aux différents modules à effectuer.
     * Particularité: elle n'est pas directement instanciable via un new Model() car son constructeur est privé.
     * Il faut utiliser la méthode get_model(), qui permet d'instancier la classe, une seule instance sera créée (donc une seule connexion à la base).
     */
    class Model {
        /**
         * @var PDO
         */
        protected PDO $bdd;
        /**
         * @var array
         */
        private array $config;
        /**
         * @var Model|null
         */
        private static ?Model $instance = null;

        /**
         * Constructeur privé
         * @param $config
         */
        private function __construct($config)
        {
            $this->config = $config;
            try{
                $this->bdd = new PDO($this->config['bdd']['dsn'], $this->config['bdd']['username'], $this->config['bdd']['password']);
            }catch(PDOException $e){
                $erreur = 'Connexion échouée: '. $e->getMessage();
                error_log($erreur);
                header("Location: ../../erreur/?erreur=500");
                exit();
            }
        }

        /**
         * Méthode qui permet d'instancier la classe
         * @param $config
         * @return Model|null
         */
        public static function get_model($config): ?Model
        {
            if(is_null(self::$instance)){
                self::$instance = new Model($config);
            }
            return self::$instance;
        }

    }