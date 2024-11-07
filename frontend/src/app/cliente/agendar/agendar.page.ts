import { Component, OnInit } from '@angular/core';
import { UserService } from 'src/app/services/user.service';

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

  constructor(private userService: UserService) { }

  ngOnInit() {
    this.userService.getMechanics().then((response: any) => {
      const mechanicsData = response.data.mechanics;
      this.mechanics = Object.keys(mechanicsData).map(key => ({
        id: Number(key),
        name: mechanicsData[key]
      }));

      console.log('mecanicos', this.mechanics);
    }).catch(error => {
      console.error('Error al obtener los mecánicos:', error);
    });
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
}
