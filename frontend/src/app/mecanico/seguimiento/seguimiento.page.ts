import { Component, OnInit } from '@angular/core';
import { AlertController, NavController } from '@ionic/angular';
import { Storage } from '@ionic/storage-angular';
import { ManteciService } from 'src/app/services/manteci.service';

@Component({
  selector: 'app-seguimiento',
  templateUrl: './seguimiento.page.html',
  styleUrls: ['./seguimiento.page.scss'],
})
export class SeguimientoPage implements OnInit {
  maintenanceId: number | null = null;
  maintenanceStatus: any = null;

  constructor(
    private alertController: AlertController,
    private navCtrl: NavController,
    private storageService: Storage,
    private manteciService: ManteciService
  ) {}

  async ngOnInit() {
    await this.storageService.create();

    const storedMaintenanceId = await this.storageService.get('idmantesion');
    if (storedMaintenanceId) {
      this.maintenanceId = storedMaintenanceId;
      console.log('ID de mantenimiento recuperado:', this.maintenanceId);
      await this.loadMaintenanceStatus();
    } else {
      console.log('No se encontró un ID de mantenimiento en Storage');
    }
  }

  async loadMaintenanceStatus() {
    if (this.maintenanceId !== null) {
      try {
        this.maintenanceStatus = await this.manteciService.getMaintenanceStatus(this.maintenanceId);
        console.log('Estado de mantenimiento:', this.maintenanceStatus.status);
      } catch (error) {
        console.error('Error al cargar el estado de mantenimiento:', error);
        this.maintenanceStatus = { status: 'Error loading status' };
      }
    }
  }

  goBack() {
    this.navCtrl.back();
  }
  async presentAlert() {
    const alert = await this.alertController.create({
      header: 'Confirmación',
      message: '¿Estás seguro de querer finalizar el estado?',
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
            if (this.maintenanceId !== null) {
              try {
                const updateResponse = await this.manteciService.updateMaintenanceStatusToNext(this.maintenanceId);
  
                if (updateResponse) {
                  console.log('Estado de mantenimiento actualizado');
                  
                  // Reload the maintenance status after the update
                  await this.loadMaintenanceStatus();
  
                  // Check if the maintenance status is 'Finalizado' before redirecting
                  if (this.maintenanceStatus.status === 'Finalizado') {
                    const successAlert = await this.alertController.create({
                      header: 'Servicio Finalizado',
                      message: 'La cotización ha pasado por todos los ciclos y ha sido finalizada exitosamente.',
                      buttons: [
                        {
                          text: 'OK',
                          handler: () => {
                            this.navCtrl.navigateForward('/mecanico/home-mecanico');
                          }
                        }
                      ],
                    });
                    await successAlert.present();
                  } else {
                    // Redirect to home-mecanico if not 'Finalizado'
                    this.navCtrl.navigateForward('/mecanico/home-mecanico');
                  }
                } else {
                  console.error('Error al actualizar el estado de mantenimiento');
                }
              } catch (error) {
                console.error('Error en la actualización del estado de mantenimiento:', error);
  
                const errorAlert = await this.alertController.create({
                  header: 'Error',
                  message: 'Hubo un problema al actualizar el estado del mantenimiento.',
                  buttons: ['OK'],
                });
  
                await errorAlert.present();
              }
            }
          },
        },
      ],
    });
  
    await alert.present();
  }
  
  

}
