import { Component, OnInit } from '@angular/core';
import { AlertController, NavController } from '@ionic/angular';
import { Storage } from '@ionic/storage-angular';
import { ManteciService } from 'src/app/services/manteci.service';  // Import your service here

@Component({
  selector: 'app-info-ser-cli',
  templateUrl: './info-ser-cli.page.html',
  styleUrls: ['./info-ser-cli.page.scss'],
})
export class InfoSerCliPage implements OnInit {

  maintenanceId: number | null = null;
  maintenanceDetails: any = null;
  serviceList: string[] = [];

  constructor(
    private alertController: AlertController,
    private navCtrl: NavController,
    private storageService: Storage,
    private manteciService: ManteciService  // Inject the service
  ) {}

  async ngOnInit() {
    // Initialize the storage service
    await this.storageService.create();

    // Retrieve the maintenance ID from storage
    const storedMaintenanceId = await this.storageService.get('idmantesion');
    if (storedMaintenanceId) {
      this.maintenanceId = storedMaintenanceId;
      console.log('ID de mantenimiento recuperado:', this.maintenanceId);

      // Ensure maintenanceId is not null before calling the service
      if (this.maintenanceId !== null) {
        this.fetchMaintenanceDetails(this.maintenanceId);
      }
    } else {
      console.log('No se encontró un ID de mantenimiento en Storage');
    }
  }

  // Fetch maintenance details using the retrieved maintenance ID
  async fetchMaintenanceDetails(maintenanceId: number) {
    const details = await this.manteciService.getMaintenanceDetails(maintenanceId);
    if (details) {
      this.maintenanceDetails = details;
      this.serviceList = Object.values(details.services || {});
      console.log('Detalles de mantenimiento obtenidos:', this.maintenanceDetails);
    } else {
      console.error('No se pudieron obtener los detalles del mantenimiento');
    }
  }

  goBack() {
    this.navCtrl.back();
  }

  async presentAlert() {
    const alert = await this.alertController.create({
      header: 'Confirmación',
      message: '¿estás seguro de querer iniciar el servicio?',
      backdropDismiss: true, 
      buttons: [
        {
          text: 'Cancelar',
          role: 'cancel',
          handler: () => {
            console.log('Acción cancelada');
          },
        },
        {
          text: 'Aceptar',
          handler: async () => {
            console.log('Acción aceptada');
            this.navCtrl.navigateForward('/mecanico/seguimiento');
          },
        },
      ],
    });

    await alert.present();
  }
}