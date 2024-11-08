import { Component, OnInit } from '@angular/core';
import { UserService } from 'src/app/services/user.service';
import { NavController } from '@ionic/angular';
import { StorageService } from 'src/app/services/storage.service';
import { carViewInterface } from 'src/app/intefaces/car';


@Component({
  selector: 'app-agendar',
  templateUrl: './agendar.page.html',
  styleUrls: ['./agendar.page.scss'],
})
export class AgendarPage implements OnInit {
  isModalOpen: boolean = false;
  mechanics: any[] = [];
  mechanicSelected: any;
  isMechanicSelected: boolean = false;
  carSelected!: carViewInterface;

  constructor(private userService: UserService, private navCtrl: NavController, private storageService: StorageService) { }

  async ngOnInit() {
    try {
      await this.storageService.init();

      this.userService.getMechanics().then((response: any) => {
        const mechanicsData = response.data.mechanics;
        mechanicsData.forEach((mechanic: any) => {
          this.mechanics.push(mechanic);
        })
      }).catch(error => {
        console.error('Error al obtener los mecánicos:', error);
      });

      const carSelected = await this.storageService.get('carSelected');

      if (carSelected) {
        if (!carSelected.patente) {
          carSelected.patente = 'Sin patente';
        }

        this.carSelected = carSelected;
        console.log('Carro seleccionado:', this.carSelected);
      } else {
        console.log('No hay carro seleccionado en el almacenamiento.');
      }

    } catch (error) {
      console.error('Error al cargar los datos:', error);
    }
  }

  setOpen(isOpen: boolean) {
    this.isModalOpen = isOpen;
  }

  selectMechanic(mechanic: any) {
    this.mechanicSelected = mechanic;
    this.isMechanicSelected = true;
    this.setOpen(false);
    console.log('Mecánico seleccionado:', this.mechanicSelected);
  }

  goBack() {
    this.navCtrl.back();
  }

  ionViewDidLeave() {
    this.storageService.remove('carSelected');
  }
}
