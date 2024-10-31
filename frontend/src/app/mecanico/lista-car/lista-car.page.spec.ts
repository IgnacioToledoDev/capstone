import { ComponentFixture, TestBed } from '@angular/core/testing';
import { ListaCarPage } from './lista-car.page';

describe('ListaCarPage', () => {
  let component: ListaCarPage;
  let fixture: ComponentFixture<ListaCarPage>;

  beforeEach(() => {
    fixture = TestBed.createComponent(ListaCarPage);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
