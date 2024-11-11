import { Component, OnInit } from '@angular/core';
import { UserService } from 'src/app/services/user.service';
import { NavController } from '@ionic/angular';
import { StorageService } from 'src/app/services/storage.service';
import { carViewInterface } from 'src/app/intefaces/car';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';

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
  isWeekday = (dateString: string) => {
    const date = new Date(dateString);
    const utcDay = date.getUTCDay();
    return utcDay !== 0;
  };
  scheduleObject = {
    mechanicId: null,
    carId: null,
    typeOfService: null,
    scheduleDate: null,
  };
  formGroup!: FormGroup;
  isValid: boolean = false;

  constructor(
    private userService: UserService,
    private navCtrl: NavController,
    private storageService: StorageService,
    private fb: FormBuilder,
  ) {}

  async ngOnInit() {
    this.formGroup = this.fb.group({
      mechanicId: [null, Validators.required],
      typeOfServiceId: [null, Validators.required],
      schedule: [null, Validators.required],
    });

    try {
      await this.storageService.init();
      this.userService
        .getMechanics()
        .then((response: any) => {
          const mechanicsData = response.data.mechanics;
          mechanicsData.forEach((mechanic: any) => {
            this.mechanics.push(mechanic);
          });
        })
        .catch((error) => {
          console.error('Error al obtener los mecánicos:', error);
        });
      const carSelected = await this.storageService.get('carSelected');
      if (carSelected) {
        if (!carSelected.patente) {
          carSelected.patente = 'Sin patente';
        }

        this.carSelected = carSelected;
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
  }

  goBack() {
    this.navCtrl.back();
  }

  ionViewDidLeave() {
    this.storageService.remove('carSelected');
  }

  onSubmit(): void {
    if (this.formGroup.valid) {
      this.isValid = true;
      const { mechanicId, typeOfServicesId, scheduleDate } =
        this.formGroup.value;
      const carId = this.carSelected.id;
      console.log(mechanicId, carId, typeOfServicesId, scheduleDate);
    }
  }
}
