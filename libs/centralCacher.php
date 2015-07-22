<?php
	/**
	 * CentralCacher Class
	 * Class to Handle Multi-DataStore For VBulletin V4.x
	 * @author abdelrahman salem 
	 * @name centralCacher
	 * @date 14/Feb/2011 [Valentine Day :P]
	 * with 4 methods : get , set , delete ,replace  and close
	 */
	class centralCacher{
		private $memCacheObject ;
		private $nodes ;
		private $port ;
		public $forumName ;
	
		public function __construct($cacheNodes,$port = 11211,$forumName = ''){
			try{
				$this->memCacheObject = new Memcache ;
				$this->forumName = $forumName ;
				$this->nodes = $cacheNodes;
				$this->port = $port ;
				//add servers
				foreach ($this->nodes as $value){
					$this->memCacheObject->addServer($value,$this->port);

					$this->memCacheObject->setCompressThreshold(900000, 0.3);
				}
			}catch(ErrorException $e){
				//prepare an error logger
				trigger_error('Unable to connect to memcache server', E_USER_ERROR);
			}
		}//End Counstructor
	
		public function get($key){
                    try{
                       return $this->memCacheObject->get($key);
                    }catch(ErrorException $e){
                        error_log('MEMCACHE ERROR' .$e->getMessage()) ;
                        return false ;
                    }
		}//End get Function
	
		public function set($key,$data,$startTime = 0,$timeToLive = 900){
                    try{
                        return $this->memCacheObject->set($key,$data, $startTime,$timeToLive );
                    }catch(ErrorException $e){
                        error_log('MEMCACHE ERROR' .$e->getMessage()) ;
                        return false ;
                    }
		}//End function set
		
		public function replace($key,$data,$startTime = 0 ,$timeToLive = 900){
			try{
				return $this->memCacheObject->replace($key,$data,$startTime ,$timeToLive);
			}catch(ErrorException $e){
				return false ;
			}
		}//End function replace
	
		public function delete($key){
			try{
				return $this->memCacheObject->delete($key);
			}catch(ErrorException $e){
				return false ;
			}
		}//End function delete
	
		public  function getStatus(){
			try{
				return $this->memCacheObject->getExtendedStats();
			}catch(ErrorException $e){
				return false ;
			}
		}//End extended State
		
		public function close(){
			$this->memCacheObject->close();
		}//End function close
	
		public function  __destruct(){
			$this->memCacheObject->close();
		}//End function close
	
	}//End Class
?>
