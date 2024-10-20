import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { UserService } from 'src/app/services/user.service';
import { NavController, AlertController } from '@ionic/angular';

@Component({
  selector: 'app-inicio-sesion',
  templateUrl: './inicio-sesion.page.html',
  styleUrls: ['./inicio-sesion.page.scss'],
})
export class InicioSesionPage implements OnInit {
  loginForm!: FormGroup;

  constructor(
    private fb: FormBuilder,
    private userService: UserService,
    private navCtrl: NavController,
    private alertController: AlertController
  ) {}

  ngOnInit() {
    this.loginForm = this.fb.group({
      email: ['', [Validators.required, Validators.email]],
      password: ['', [Validators.required]],
    });
  
    this.checkIfAuthenticated();
  }

  async onSubmit() {
    if (this.loginForm.valid) {
      const { email, password } = this.loginForm.value;
      try {
        const response: any = await this.userService.login({ email, password });
        console.log('Inicio de sesión exitoso:', response);
        
        const userRoles = response.data.user.roles; 
        console.log('Roles del usuario:', userRoles);
        if (userRoles.includes('MECHANIC_USER')) {
          this.navCtrl.navigateForward('/mecanico/home-mecanico');
        } else if (userRoles.includes('CUSTOMER_USER')) {
          this.navCtrl.navigateForward('/cliente/home-cliente');
        } else {
          this.presentAlert('Error de rol', 'Rol desconocido.');
        }
      } catch (error) {
        console.error('Error de inicio de sesión:', error);
        this.presentAlert('Error de inicio de sesión', 'Correo o contraseña incorrectos');
      }
    }
  }
  

  async presentAlert(header: string, message: string) {
    const alert = await this.alertController.create({
      header,
      message,
      buttons: ['OK'],
    });
    await alert.present();
  }

  async checkIfAuthenticated() {
    const sessionData = await this.userService.getUserSession(); // Obtener la sesión almacenada
  
    if (sessionData) {
      const userRoles = sessionData.user.roles;
      console.log('Roles del usuario autenticado:', userRoles);
  
      // Verificar los roles y redirigir a la página correspondiente
      if (userRoles.includes('MECHANIC_USER')) {
        this.navCtrl.navigateForward('/mecanico/home-mecanico');
      } else if (userRoles.includes('CUSTOMER_USER')) {
        this.navCtrl.navigateForward('/cliente/home-cliente');
      } else {
        this.presentAlert('Error de rol', 'Rol desconocido.');
      }
    } else {
      console.log('Usuario no autenticado.');
    }
  }
  
}