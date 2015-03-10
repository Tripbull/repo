<?php

class foto{
	public $alt;
	public $tam;
	public $img;
	public $name;
	function foto($name){
		$dim = getimagesize($name);
		$this->tam = $dim[0];
		$this->alt = $dim[1];
		$this->img = imagecreatefromjpeg($name);
		$this->name = $name;
	}
}
class fotoPng{
	public $alt;
	public $tam;
	public $img;
	public $name;
	function fotoPng($name){
		$dim = getimagesize($name);
		$this->tam = $dim[0];
		$this->alt = $dim[1];
		$this->img = imagecreatefrompng($name);
		$this->name = $name;
	}
}


class mergePictures{
	/**
	 * Imagem 1 var
	 *
	 * @var Foto
	 */
	public $foto1;
	
	/**
	 * Image2 var
	 *
	 * @var Foto
	 */
	public $foto2;
	
	/**
	 * Destination Image
	 *
	 * @var Resource imagecreatetruecolor
	 */
	public $image;
	
	/**
	 * resize percentage of image 2
	 *
	 * @var float
	 */
	public $perc;
	
	/**
	 * co-ordinated X
	 *
	 * @var float
	 */
	public $x;
	
	/**
	 * co-ordinated Y
	 *
	 * @var float
	 */
	public $y;

	/**
	 * Class constructor
	 *
	 * @param String $file1 File Name1
	 * @param String $file2 File Name2
	 * @return mergePictures
	 * 
	 * @uses $file1 and $file2 has to be a JPG or PNG file
	 */
	 function mergePictures2($dest,$src,$nameFile,$src_width=380,$src_heigth=82,$dest_y_coor=300,$transparent = 70){
		imagecopymerge($dest, $src, 0, $dest_y_coor, 0, 0, $src_width, $src_heigth, $transparent);
		imagejpeg($dest, $nameFile.".jpg");
	 }
	function mergePictures($file1,$file2){

		//  it verifies which is the source picture type
		if(strtolower( substr($file1,strlen($file1)-3 ,strlen($file1)))=="png"){
			$this->foto1 = new fotoPng($file1);
			$png = true;
		}else {
			$png = false;
			$this->foto1 = new foto($file1);
			
		}
		
		
		//same thing for the picture 2
		if(strtolower( substr($file2,strlen($file2)-3 ,strlen($file2)))=="png"){
			$this->foto2 = new fotoPng($file2);
		}else{
			$this->foto2 = new foto($file2);
		}
		
		//percentage of reduction of imagem2
		$this->perc = 1;
		if ($this->foto1->tam < $this->foto2->tam) {
			$this->perc= ($this->foto2->tam - $this->foto1->tam)*100/ $this->foto2->tam ;
			$this->perc =1- $this->perc/100;
		}
		if ($this->foto1->tam > $this->foto2->tam) {
			$this->perc= ($this->foto1->tam - $this->foto2->tam)*100/ $this->foto2->tam ;
			$this->perc = 1 + $this->perc/100;
		}

		$this->x = $this->foto1->tam;
		$this->y = $this->foto1->alt + $this->foto2->alt*$this->perc;
	}

	/**
	 * it catches image1 and it places on proportionally reduced image2
	 *
	 */
	function over(){
	

		$this->image = imagecreatetruecolor($this->x,$this->foto2->alt*$this->perc );

		imagecopyresampled($this->image,$this->foto2->img,0, 0,0,0 , $this->x ,$this->foto2->alt*$this->perc, $this->foto2->tam ,$this->foto2->alt);
		imagecopy( $this->image, $this->foto1->img , 0 ,($this->foto2->alt*$this->perc)-$this->foto1->alt , 0, 0, $this->foto1->tam, $this->foto1->alt);

	}
	/**
	 * it catches proportionally reduced image 2 and it joins it with imagem1
	 *
	 * @param String $pos The position where image 1 will be(up or down)
	 */
	function merge($pos){

		$this->image = imagecreatetruecolor($this->x,$this->y);
		//	imagealphablending( $this->image, true );

		switch ($pos) {
			case "up":
				imagecopy( $this->image, $this->foto1->img , 0 , $this->foto2->alt*$this->perc , 0, 0, $this->foto1->tam, $this->foto1->alt);
				imagecopyresampled($this->image,$this->foto2->img,0, 0,0,0 , $this->foto1->tam ,$this->y-$this->foto1->alt , $this->foto2->tam ,$this->foto2->alt);

				break;
			case "down":
				imagecopy( $this->image, $this->foto1->img , 0 , 0, 0, 0, $this->foto1->tam, $this->foto1->alt);
				imagecopyresampled($this->image,$this->foto2->img,0, $this->foto1->alt,0,0 , $this->foto1->tam ,$this->y-$this->foto1->alt , $this->foto2->tam ,$this->foto2->alt);

				break;

		}

	}
	/**
	 * Saves the new picture
	 *
	 * @param String $dirName Path Location
	 * @param String $nameFile File Name
	 * @param String $ext File extension (JPG,PNG or GIF)
	 */	
	function save($dirName, $nameFile, $ext){
		if($dirName!=""){
			$dirName.="/";
		}
		
		if($nameFile!="" and $ext!="" and ($ext=="jpg" or $ext=="gif" or $ext="png")){			
			switch (strtolower($ext)){
				case "jpg":
					imagejpeg($this->image, $dirName.$nameFile.".jpg");
					break;
				case "gif":
					imagegif($this->image, $dirName.$nameFile.".gif");
					break;
				case "png":
					imagepng($this->image, $dirName.$nameFile.".png");
					break;
			}
		}

	}

}
?>
