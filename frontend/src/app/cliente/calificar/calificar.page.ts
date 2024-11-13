import { Component, OnInit } from '@angular/core';
import { NavController, AlertController } from '@ionic/angular';
import { Storage } from '@ionic/storage-angular'; // Importa Storage
import { UserService } from 'src/app/services/user.service';  // Asegúrate de cambiar 'YourServiceName' al nombre correcto de tu servicio

@Component({
  selector: 'app-calificar',
  templateUrl: './calificar.page.html',
  styleUrls: ['./calificar.page.scss'],
})
export class CalificarPage implements OnInit {
  mechanicId: number | null = null; // Propiedad para almacenar mechanicId
  stars: number[] = [1, 2, 3, 4, 5];
  rating: number = 0;
  comment: string = '';

  constructor(
    private navCtrl: NavController,
    private alertCtrl: AlertController,
    private storageService: Storage, // Inyecta Storage
    private UserService: UserService // Inyecta tu servicio aquí
  ) {}

  async ngOnInit() {
    // Inicializa el servicio de almacenamiento
    await this.storageService.create();

    // Recupera el mechanicId de Storage
    this.mechanicId = await this.storageService.get('mechanicId');
    console.log('ID de mecánico cargado:', this.mechanicId);
  }

  goBack() {
    this.navCtrl.back();
  }

  rate(star: number) {
    this.rating = star;
  }

  async submitRating() {
    if (!this.mechanicId) {
      console.error('No se ha encontrado el ID del mecánico');
      return;
    }

    try {
      // Llama al servicio setMechanicScore con mechanicId, rating y comment
      const response = await this.UserService.setMechanicScore(this.mechanicId, this.rating, this.comment);
      if (response) {
        console.log('Calificación enviada con éxito:', response);

        const alert = await this.alertCtrl.create({
          header: 'Gracias por tu calificación',
          message: 'Tus respuestas nos serán de mucha utilidad para mejorar este servicio.',
          buttons: [{
            text: 'Cerrar',
            handler: () => {
              this.navCtrl.navigateRoot('/cliente/home-cliente');
            }
          }]
        });

        await alert.present();

        // Restablece valores
        this.rating = 0;
        this.comment = '';
      } else {
        console.error('No se pudo enviar la calificación');
      }
    } catch (error) {
      console.error('Error al enviar la calificación del mecánico:', error);
    }
  }
}
