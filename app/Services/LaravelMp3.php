<?php
namespace App\Services;
include_once('../getid3/getid3.php');
include_once('../getid3/write.php');

class LaravelMp3{
	private $id3, $tagWriter, $folder, $song, $tags;

	public function __construct(){
		$this->id3 = new \getID3;
		$this->tagWriter = new \getid3_writetags;
		$this->folder = storage_path().'/songs/';
	}

	public function load($pathToFile){
		$this->song =  $this->folder.$pathToFile;
		$this->getSongTags();
		return $this;
	}

	public function areTagsPresent(){
		$info = $this->id3->analyze($this->song);
		
		if(array_key_exists('tags', $info)){
			return true;
		} 
		else{
			return false;
		}
	}

	/* ----------------------------- Getter Functions --------------------------------------- */
	public function getSongTags(){
		$info = $this->id3->analyze($this->song);
		if(array_key_exists('tags', $info)){
			foreach ($info['tags']['id3v2'] as $key => $value) {
				$this->tags[$key] = $value[0];
			}

			$this->tags['album_artist'] = $this->tags['band'];
			$this->tags['mime_type'] = $info['mime_type'];
			$this->tags['duration'] = $info['playtime_string'];
			unset($this->tags['band']);
			return $this->tags;
		} 
		else{
			return false;
		}
	}

	public function getTitle(){
		return $this->tags['title'];
	}

	public function getAlbum(){
		return $this->tags['album'];
	}

	public function getTrackNumber(){
		return $this->tags['track_number'];
	}

	public function getSongArtist(){
		return $this->tags['artist'];
	}

	public function getAlbumArtist(){
		return $this->tags['album_artist'];	
	}

	public function getYear(){
		return $this->tags['year'];	
	}

	public function getGenre(){
		return $this->tags['genre'];	
	}

	public function getDuration(){
		return $this->tags['duration'];	
	}

	public function getMime(){
		return $this->tags['mime_type'];	
	}

	/* ------------------------- Setter Functions ----------------------- */
	public function setTags($details){
		//Setup for GetID3
		$textEncoding = 'UTF-8';
		$this->id3->setOption(array('encoding'=>$textEncoding));
		$this->tagWriter->filename = $this->song;
		$this->tagWriter->tagformats = array('id3v2.3');
		$this->tagWriter->overwrite_tags = true; 
		$this->tagWriter->remove_other_tags = false; 
		$this->tagWriter->tag_encoding = $textEncoding;

		$tagData = array();

		foreach($details as $key=>$value){
			if($key==='album_artist'){
				$tagData['band'] = array($value);
				continue;
			}
			$tagData[$key] = array($value);
		}

		$this->tagWriter->tag_data = $tagData;

		if($this->tagWriter->WriteTags()){
			return true;
		} 
		else{
			return false;
		}
	}



}