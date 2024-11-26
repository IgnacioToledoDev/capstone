import { Component, OnInit, OnDestroy } from '@angular/core';
import { AlertController, NavController } from '@ionic/angular';
import { Storage } from '@ionic/storage-angular';
import { CarService } from 'src/app/services/car.service';

@Component({
  selector: 'app-escanear-patente',
  templateUrl: './escanear-patente.page.html',
  styleUrls: ['./escanear-patente.page.scss'],
})
export class EscanearPatentePage implements OnInit, OnDestroy {
  patent: string = ''; // Para almacenar la patente ingresada
  private storageCheckInterval: any; // Intervalo para chequear cambios en el Storage

  constructor(
    private alertController: AlertController,
    private navCtrl: NavController,
    private storageService: Storage,
    private carService: CarService
  ) {}

  async ngOnInit() {
    // Inicializar Storage y cargar datos
    await this.storageService.create();
    await this.loadPatentFromStorage();

    // Configurar un intervalo para chequear cambios en Storage
    this.storageCheckInterval = setInterval(async () => {
      await this.loadPatentFromStorage();
    }, 1000); // Chequear cada segundo (ajusta según tus necesidades)
  }

  ngOnDestroy() {
    // Limpiar el intervalo al destruir el componente
    if (this.storageCheckInterval) {
      clearInterval(this.storageCheckInterval);
    }
  }

  async loadPatentFromStorage() {
    const storedPatent = await this.storageService.get('scanpatente');
    if (storedPatent && storedPatent !== this.patent) {
      this.patent = storedPatent; // Actualizar la patente solo si cambia
    }
  }

  goBack() {
    this.navCtrl.back();
  }

  async searchCarByPatent() {
    if (!this.patent) {
      const alert = await this.alertController.create({
        header: 'Error',
        message: 'Por favor, ingresa una patente.',
        buttons: ['OK']
      });
      await alert.present();
      return;
    }

    try {
      const carData = await this.carService.getCarByPatent(this.patent);
      
      // Eliminar los datos anteriores de coche almacenados en el Storage
      await this.storageService.remove('scanpatente');
      console.log('Datos de coche eliminados del Storage');

      if (carData) {
        // Guardar los datos del coche en el Storage user mecanico/qrinfo
        await this.storageService.set('userDataQR', carData);
        console.log('Coche encontrado y guardado:', carData);

        const alert = await this.alertController.create({
          header: 'Carro encontrado',
          message: `Cliente actual`,
          buttons: ['OK']
        });
        await alert.present();

        // Redirigir a la página mecanico/qrinfo después de encontrar el coche
        this.navCtrl.navigateForward('/mecanico/qrinfo');
      } else {
        const alert = await this.alertController.create({
          header: 'Error',
          message: 'No se encontró ningún coche con esa patente.',
          buttons: ['OK']
        });
        await alert.present();
      }
    } catch (error) {
      console.error('Error al buscar el coche:', error);
      const alert = await this.alertController.create({
        header: 'Error',
        message: 'Hubo un problema al buscar el coche. Inténtalo de nuevo.',
        buttons: ['OK']
      });
      await alert.present();
    }
  }
}
