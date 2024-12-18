import { Component, OnInit } from '@angular/core';
import { UserService } from 'src/app/services/user.service';
import { NavController } from '@ionic/angular';
import { StorageService } from 'src/app/services/storage.service';
import { carViewInterface } from 'src/app/intefaces/car';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import {CotizaService} from "../../services/cotiza.service";
import {ReservationService} from "../../services/reservation.service";
import {ReservationInterface} from "../../intefaces/reservation";

@Component({
  selector: 'app-agendar',
  templateUrl: './agendar.page.html',
  styleUrls: ['./agendar.page.scss'],
})
export class AgendarPage implements OnInit {
  isModalOpen: boolean = false;
  mechanics: any[] = [];
  typesServices: { id: number, name: string }[] = [];
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
    private servicesService: CotizaService,
    private reservationService: ReservationService
  ) {}

  async ngOnInit() {
    this.formGroup = this.fb.group({
      mechanicId: ['', Validators.required],
      typeOfServiceId: ['', Validators.required],
      schedule: ['', Validators.required],
    });

    this.typesServices = await this.servicesService.getServiceTypes();

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

  selectMechanic(mechanic: any) {
    this.mechanicSelected = mechanic;
    this.isMechanicSelected = true;
  }

  goBack() {
    this.navCtrl.back();
  }

  ionViewDidLeave() {
    this.storageService.remove('carSelected');
  }

  onSubmit(): void {
    if (this.formGroup.valid) {
      console.log('click');
      this.isValid = true;

      const { mechanicId, typeOfServiceId, schedule } = this.formGroup.value;
      const carId = this.carSelected.id;
      const reservationDate: string = schedule;

      // Crear la reserva usando los valores obtenidos del formulario
      this.reservationService
        .createReservation({
          mechanicId,
          carId,
          typeOfServiceId: parseInt(typeOfServiceId, 10), // Asegúrate de convertir a número
          reservationDate
        } as ReservationInterface)
        .then((res) => {
          if (res) {
            alert('Reserva creada con éxito');
          } else {
            alert('Hubo un error al crear la reserva');
          }
        })
        .catch((error) => {
          console.error('Error al crear la reserva:', error);
          alert('Ocurrió un error al procesar la reserva.');
        });
    }
  }

}
