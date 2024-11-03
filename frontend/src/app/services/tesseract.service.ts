import { Injectable } from '@angular/core';
import * as Tesseract from 'tesseract.js';

@Injectable({
  providedIn: 'root'
})
export class TesseractService {
  constructor() {}

  public recognizeText(image: File): Promise<string> {
    return new Promise((resolve, reject) => {
      Tesseract.recognize(
        image,
        'eng', // Puedes cambiar el idioma si es necesario
        {
          logger: (m: { status: string }) => console.log(m) // Opcional: puedes ver el progreso
        }
      )
      .then(({ data }: { data: { text: string } }) => {
        const { text } = data;
        console.log('Texto reconocido:', text); // Agrega esto para ver el resultado en consola
        resolve(text);
      })
      .catch((error: any) => {
        console.error('Error al reconocer texto:', error); // Mejora la gestión de errores
        reject(error);
      });
    });
  }
}
