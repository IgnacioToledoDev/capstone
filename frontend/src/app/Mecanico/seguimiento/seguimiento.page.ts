import { Component, OnInit } from '@angular/core';
import { AlertController ,NavController} from '@ionic/angular';

interface Status {
  title: string;
  description: string;
  image: string;
  buttonTitle?: string;
}
@Component({
  selector: 'app-seguimiento',
  templateUrl: './seguimiento.page.html',
  styleUrls: ['./seguimiento.page.scss'],
})
export class SeguimientoPage implements OnInit {

  constructor(private navCtrl: NavController) { }

  ngOnInit() {
  }
  goBack() {
    this.navCtrl.back();
  }

  statuses: Status[] = [
    {
      title: 'Iniciado',
      description: '',
      image: 'assets/images/inicio.jpg',
      buttonTitle: 'Actual'
    },
    {
      title: 'En progreso',
      description: '',
      image: 'assets/images/proximo.jpg',
      buttonTitle: 'Próximo...'
    },
    {
      title: 'Listo para entrega',
      description: '',
      image: 'assets/images/entrega.jpg',
      buttonTitle: 'Próximo...'
    },
  ];


  onButtonClick(status: Status) {
    console.log( status.title);

  }
  presentAlert(){
    console.log('Acción Finalizar');
  }
}