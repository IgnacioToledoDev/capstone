import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { AgendaCarLisPage } from './agenda-car-lis.page';

const routes: Routes = [
  {
    path: '',
    component: AgendaCarLisPage
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class AgendaCarLisPageRoutingModule {}
