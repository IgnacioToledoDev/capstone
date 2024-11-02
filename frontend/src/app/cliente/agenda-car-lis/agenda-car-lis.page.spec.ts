import { ComponentFixture, TestBed } from '@angular/core/testing';
import { AgendaCarLisPage } from './agenda-car-lis.page';

describe('AgendaCarLisPage', () => {
  let component: AgendaCarLisPage;
  let fixture: ComponentFixture<AgendaCarLisPage>;

  beforeEach(() => {
    fixture = TestBed.createComponent(AgendaCarLisPage);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
