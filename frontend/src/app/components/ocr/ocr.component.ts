import { Component, OnInit } from '@angular/core';
import { TesseractService } from 'src/app/services/tesseract.service'; 
import { Storage } from '@ionic/storage-angular'; // Importar Storage

@Component({
  selector: 'app-ocr',
  templateUrl: './ocr.component.html',
  styleUrls: ['./ocr.component.scss'],
})
export class OcrComponent implements OnInit {

  public recognizedText: string = '';
  public selectedImage: File | null = null;

  constructor(
    private tesseractService: TesseractService,
    private storageService: Storage // Inyectar Storage
  ) {}

  ngOnInit() {}

  onFileSelected(event: any) {
    this.selectedImage = event.target.files[0];
  }

  async recognizeText() {
    if (this.selectedImage) {
      try {
        const text = await this.tesseractService.recognizeText(this.selectedImage);

        // Filtrar solo letras y números del texto reconocido
        const filteredText = text.replace(/[^a-zA-Z0-9]/g, '');

        // Guardar el texto filtrado en el almacenamiento
        await this.storageService.set('scanpatente', filteredText);

        // Mostrar el texto reconocido y filtrado
        this.recognizedText = filteredText;

      } catch (error) {
        console.error('Error al reconocer texto:', error);
      }
    }
  }
}
