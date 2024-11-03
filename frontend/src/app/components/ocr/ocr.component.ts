import { Component, OnInit } from '@angular/core';
import { TesseractService } from 'src/app/services/tesseract.service'; 

@Component({
  selector: 'app-ocr',
  templateUrl: './ocr.component.html',
  styleUrls: ['./ocr.component.scss'],
})
export class OcrComponent  implements OnInit {

  ngOnInit() {}

  public recognizedText: string = '';
  public selectedImage: File | null = null;

  constructor(private tesseractService: TesseractService) {}

  onFileSelected(event: any) {
    this.selectedImage = event.target.files[0];
  }

  recognizeText() {
    if (this.selectedImage) {
      this.tesseractService.recognizeText(this.selectedImage).then((text) => {
        this.recognizedText = text;
      }).catch((error) => {
        console.error(error);
      });
    }
  }
}