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

  constructor(private userService: UserService) { }

  ngOnInit() {
    this.userService.getMechanics().then((response: any) => {
      const mechanicsData = response.data.mechanics; // Ajusta el acceso según la estructura de tu objeto
      this.mechanics = Object.keys(mechanicsData).map(key => ({
        id: Number(key),
        name: mechanicsData[key]
      }));

      console.log('mecanicos', this.mechanics); // Debería mostrar el arreglo correctamente estructurado
    }).catch(error => {
      console.error('Error al obtener los mecánicos:', error);
    });
  }

  setOpen(isOpen: boolean) {
    this.isModalOpen = isOpen;
  }

}
