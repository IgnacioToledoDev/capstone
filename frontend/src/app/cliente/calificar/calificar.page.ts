import { Component, OnInit } from '@angular/core';
import { NavController, AlertController } from '@ionic/angular';

@Component({
  selector: 'app-calificar',
  templateUrl: './calificar.page.html',
  styleUrls: ['./calificar.page.scss'],
})
export class CalificarPage implements OnInit {

  constructor(private navCtrl: NavController, private alertCtrl: AlertController) { }

  ngOnInit() {}

  goBack() {
    this.navCtrl.back();
  }

  stars: number[] = [1, 2, 3, 4, 5];
  rating: number = 0;
  comment: string = '';

  rate(star: number) {
    this.rating = star;
  }

  async submitRating() {
    console.log('Calificación:', this.rating);
    console.log('Comentario:', this.comment);
    const alert = await this.alertCtrl.create({
      header: 'Gracias por tu calificación',
      message: `Tus respuestas nos seran de mucha utilidad para mejorar este servicio.`,
      buttons: [{
        text: 'Cerrar',
        handler: () => {
          this.navCtrl.navigateRoot('/cliente/home-cliente');
        }
      }]
    });

    await alert.present();

    // Restablecer valores
    this.rating = 0;
    this.comment = '';
  }
}
