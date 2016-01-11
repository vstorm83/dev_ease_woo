<?php 
	class Ahlu_SEO extends SEO_Util 
	{
		private $stopWords = array("a", "about", "above", "above", "across", 
      "after", "afterwards", "again", "against", "all", "almost", "alone", 
      "along", "already", "also", "although", "always", "am", "among", 
      "amongst", "amoungst", "amount", "an", "and", "another", "any", "anyhow", 
      "anyone", "anything", "anyway", "anywhere", "are", "around", "as", "at", 
      "back", "be", "became", "because", "become", "becomes", "becoming", 
      "been", "before", "beforehand", "behind", "being", "below", "beside", 
      "besides", "between", "beyond", "bill", "both", "bottom", "but", "by", 
      "call", "can", "cannot", "cant", "co", "con", "could", "couldn't", 
      "de", "detail", "do", "done", "down", "due", "during", "each", 
      "eg", "eight", "either", "eleven", "else", "elsewhere", "empty", "enough", 
      "etc", "even", "ever", "every", "everyone", "everything", "everywhere", 
      "except", "few", "fifteen", "fify", "fill", "find", "first", 
      "five", "for", "former", "formerly", "forty", "found", "four", "from", 
      "front", "full", "further", "get", "give", "go", "had", "has", "hasnt", 
      "have", "he", "hence", "her", "here", "hereafter", "hereby", "herein", 
      "hereupon", "hers", "herself", "him", "himself", "his", "how", "however", 
      "hundred", "ie", "if", "in", "inc", "indeed", "interest", "into", "is", 
      "it", "its", "itself", "keep", "last", "latter", "latterly", "least", 
      "less", "ltd", "made", "many", "may", "me", "meanwhile", "might", "mill", 
      "mine", "more", "moreover", "most", "mostly", "move", "much", "must", 
      "my", "myself", "name", "namely", "neither", "never", "nevertheless", 
      "next", "nine", "no", "nobody", "none", "noone", "nor", "not", "nothing", 
      "now", "nowhere", "of", "off", "often", "on", "once", "one", "only", 
      "onto", "or", "other", "others", "otherwise", "our", "ours", "ourselves", 
      "out", "over", "own", "part", "per", "perhaps", "please", "put", "rather", 
      "re", "same", "see", "seem", "seemed", "seeming", "seems", "serious", 
      "several", "she", "should", "show", "side", "since", "sincere", "six", 
      "sixty", "so", "some", "somehow", "someone", "something", "sometime", 
      "sometimes", "somewhere", "still", "such", "take", "ten", 
      "than", "that", "the", "their", "them", "themselves", "then", "thence", 
      "there", "thereafter", "thereby", "therefore", "therein", "thereupon", 
      "these", "they", "thin", "third", "this", "those", "though", 
      "three", "through", "throughout", "thru", "thus", "to", "together", "too", 
      "top", "toward", "towards", "twelve", "twenty", "two", "un", "under", 
      "until", "up", "upon", "us", "very", "via", "was", "we", "well", "were", 
      "what", "whatever", "when", "whence", "whenever", "where", "whereafter", 
      "whereas", "whereby", "wherein", "whereupon", "wherever", "whether", 
      "which", "while", "whither", "who", "whoever", "whole", "whom", "whose", 
      "why", "will", "with", "within", "without", "would", "yet", "you", "your", 
      "yours", "yourself", "yourselves", "ll", "t", "s", "d", "ve", "m"
   ); 
		public function __construct(){
			
			return $this->init();
		}

		////////////////
		private function init(){
			return $this;
		}
		public function setTitle($t){
			$this->vars["title"] = '<title>'.ucfirst($t).'</title>';
			return $this;
		}
		public function setKeyword($k,$nbrWords=5){
			$text = preg_replace('/\'/',' ',$k);
			  $words = str_word_count($text, 1); 
			  array_walk($words, array( 
				 $this, 
				 'filter' 
			  )); 
			  $words = array_diff($words, $this->stopWords); 
			  $wordCount = array_count_values($words); 
			  arsort($wordCount); 
			  $wordCount = array_slice($wordCount, 0, $nbrWords); 
			  
			  $k = array_keys($wordCount);
			  
			$this->vars["keyword"] = '<meta name="keywords" content="'.(count($k)==0 ? $k :implode(" ",array_keys($wordCount))).'"/>';
			return $this;
		}
		public function setDescription($d,$max=156){
		if($maxDescription<$max){
			$max = $maxDescription;
		}
		 $k = $this->strTruncate($this->varReplace($d), $max);
			$this->vars["description"] = '<meta name="Description" content="'.(empty($k)?$d:$k).'" />';
			return $this;
		}		
		public function setRewind($day){
			$this->vars["rewind"] = '<meta name="revisit-after" content="'.$day.' days" />';
		}		
		public function setLanguage($l){
			$this->vars["lang"] = '<meta http-equiv="content-language" content="'.$l.'" />';
			return $this;
		}	
		public function setCharset($charset){
			$this->vars["charset"] = '<meta http-equiv="Content-Type" content="text/html; charset='.$charset.'" />';
			return $this;
		}		
		public function setAuthor(){
			$this->vars["author"] = '<meta name="Author" content="'.$a.'" />';
			return $this;
		}		
		public function setRobot($robot){
			$this->vars["robot"] = '<meta name="robots" content="'.$robot.'" />';
			return $this;
		}		
		public function setCanonical($c){
			$this->vars["canonical"] = '<link rel="canonical" href="'.$c.'" />';
			$this->vars["shortlink"] = '<link rel="shortlink" href="'.$c.'" />';
			return $this;
		}
		public function setVerification($v){
			$this->vars["verification"] = '<meta name="google-site-verification" content="'.$v.'" />';
			return $this;
		}
		
		public function enableMobil(){
			$this->vars["viewport"] = '
				<meta name="viewport" content="width=device-width, initial-scale=1.0">'."\n".'
				<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
			';
			return $this;
		}
		
		public function Meta(){
			//enable mobil
			if(!isset($this->vars["viewport"])) $this->enableMobil();
			if(!isset($this->vars["robot"])) $this->setRobot('index, follow');
			if(!isset($this->vars["charset"])) $this->setCharset('utf-8');
			if(!isset($this->vars["lang"])) $this->setLanguage('en');
			$s ="";
			
			return implode($this->vars,"\n");
		}
		/*
		<meta content='index, follow' name='GOOGLEBOT'/>
		<meta content='index, follow' name='yahooBOT'/>
		<meta name="Slurp" content="index,follow" />

<meta name="MSNBot" content="index,follow" />
				."\n".'
		*/
		
		private function filter(&$val, $key) 
	   { 
		  $val = strtolower($val); 
	   } 
	   private function setStopWords() 
	   { 
		  $this->stopWords = array(); 
	   } 
	}
?>